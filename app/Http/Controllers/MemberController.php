<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Member;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['user', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap',    'like', $search)
                  ->orWhere('nama_panggilan', 'like', $search)
                  ->orWhere('covenant_number','like', $search)
                  ->orWhere('nomor_hp',       'like', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $members = $query->paginate(12)->withQueryString();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_lengkap'     => 'required|string|max:255',
            'nama_panggilan'   => 'nullable|string|max:100',
            'nomor_hp'         => 'nullable|string|max:20',
            'alamat'           => 'nullable|string',
            'tanggal_lahir'    => 'nullable|date',
            'covenant_number'  => 'nullable|string|max:50|unique:members,covenant_number',
            'is_active'        => 'nullable|boolean',
            // Account creation (optional)
            'create_account'   => 'nullable|boolean',
            'email'            => 'nullable|email|max:255|unique:users,email|required_if:create_account,1',
            'password'         => 'nullable|string|min:8|confirmed|required_if:create_account,1',
            'role'             => 'nullable|in:admin,icl,ctl,anggota',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('members', config('filesystems.default'));
        }

        $member = Member::create([
            'foto'            => $foto,
            'nama_lengkap'    => $request->nama_lengkap,
            'nama_panggilan'  => $request->nama_panggilan,
            'nomor_hp'        => $request->nomor_hp,
            'alamat'          => $request->alamat,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'covenant_number' => $request->covenant_number,
            'is_active'       => $request->boolean('is_active', true),
            'created_by'      => auth()->id(),
        ]);

        // Optionally create linked user account
        if ($request->boolean('create_account') && $request->filled('email')) {
            $user = User::create([
                'name'      => $member->nama_lengkap,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role ?? 'anggota',
                'is_active' => true,
                'tenant_id' => auth()->user()->tenant_id,
            ]);
            $member->update(['user_id' => $user->id]);
        }

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Member $member)
    {
        $user = $member->user ?? User::where('name', $member->nama_lengkap)->first();

        $myBadges     = collect();
        $allBadges    = Achievement::orderBy('category')->orderBy('order')->get();
        $progress     = [];
        $activityLogs = collect();

        if ($user) {
            $myBadges     = $user->achievements;
            $progress     = app(AchievementService::class)->getProgressForUser($user);
            $activityLogs = $user->activityLogs()->limit(10)->get();
        }

        return view('members.show', compact('member', 'user', 'myBadges', 'allBadges', 'progress', 'activityLogs'));
    }

    public function edit(Member $member)
    {
        $user = $member->user;
        return view('members.edit', compact('member', 'user'));
    }

    public function update(Request $request, Member $member)
    {
        $userId = $member->user?->id;

        $request->validate([
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_lengkap'    => 'required|string|max:255',
            'nama_panggilan'  => 'nullable|string|max:100',
            'nomor_hp'        => 'nullable|string|max:20',
            'alamat'          => 'nullable|string',
            'tanggal_lahir'   => 'nullable|date',
            'covenant_number' => 'nullable|string|max:50|unique:members,covenant_number,' . $member->id,
            'is_active'       => 'nullable|boolean',
            // Account fields
            'email'           => 'nullable|email|max:255|unique:users,email,' . $userId,
            'role'            => 'nullable|in:admin,icl,ctl,anggota',
            'new_password'    => 'nullable|string|min:8|confirmed',
            // Create new account if none exists
            'create_account'  => 'nullable|boolean',
            'password'        => 'nullable|string|min:8|confirmed|required_if:create_account,1',
        ]);

        // Handle photo upload
        $foto = $member->foto;
        if ($request->hasFile('foto')) {
            if ($foto) Storage::disk(config('filesystems.default'))->delete($foto);
            $foto = $request->file('foto')->store('members', config('filesystems.default'));
        }

        $member->update([
            'foto'            => $foto,
            'nama_lengkap'    => $request->nama_lengkap,
            'nama_panggilan'  => $request->nama_panggilan,
            'nomor_hp'        => $request->nomor_hp,
            'alamat'          => $request->alamat,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'covenant_number' => $request->covenant_number,
            'is_active'       => $request->boolean('is_active', true),
        ]);

        // Update existing linked account
        if ($member->user) {
            $userUpdate = ['name' => $member->nama_lengkap];

            if ($request->filled('email'))  $userUpdate['email'] = $request->email;
            if ($request->filled('role'))   $userUpdate['role']  = $request->role;
            if ($request->filled('new_password')) {
                $userUpdate['password'] = Hash::make($request->new_password);
            }

            $member->user->update($userUpdate);
        }
        // Create new account if requested and no account exists
        elseif ($request->boolean('create_account') && $request->filled('email')) {
            $user = User::create([
                'name'      => $member->nama_lengkap,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role ?? 'anggota',
                'is_active' => true,
                'tenant_id' => auth()->user()->tenant_id,
            ]);
            $member->update(['user_id' => $user->id]);
        }

        return redirect()->route('members.show', $member)
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function updateRole(Request $request, Member $member)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        $request->validate(['role' => 'required|in:admin,icl,ctl,anggota']);

        if (!$member->user) {
            return back()->with('error', 'Anggota ini belum memiliki akun yang terhubung.');
        }

        // Pastikan target user ada di tenant yang sama — cegah admin cross-tenant
        abort_unless($member->user->tenant_id === $admin->tenant_id, 403);

        // Hapus secondary_role jika role utama berubah dari admin
        $updates = ['role' => $request->role];
        if ($request->role !== 'admin') {
            $updates['secondary_role'] = null;
        }

        $member->user->update($updates);

        $label = \App\Models\User::make(['role' => $request->role])->roleLabel();
        return back()->with('success', 'Role akun berhasil diubah menjadi ' . $label . '.');
    }

    public function updateSecondaryRole(Request $request, Member $member)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        $request->validate(['secondary_role' => 'nullable|in:icl,ctl,anggota']);

        if (!$member->user) {
            return back()->with('error', 'Anggota ini belum memiliki akun yang terhubung.');
        }

        // Pastikan target user ada di tenant yang sama
        abort_unless($member->user->tenant_id === $admin->tenant_id, 403);

        if (!$member->user->isAdmin()) {
            return back()->with('error', 'Role tambahan hanya berlaku untuk akun Admin.');
        }

        $member->user->update(['secondary_role' => $request->secondary_role ?: null]);

        $label = $request->secondary_role
            ? \App\Models\User::make(['secondary_role' => $request->secondary_role])->secondaryRoleLabel()
            : 'Tidak Ada';

        return back()->with('success', 'Role tambahan berhasil diubah menjadi ' . $label . '.');
    }

    public function destroy(Member $member)
    {
        if ($member->foto) {
            Storage::disk(config('filesystems.default'))->delete($member->foto);
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
