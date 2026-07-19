<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * Kelola SEMUA user lintas tenant (bukan cuma superadmin) — lihat/tambah/hapus
 * user, dan ubah role langsung dari daftar. Superadmin tidak terikat tenant
 * manapun (tenant_id null) — lihat App\Models\Concerns\BelongsToTenant.
 */
class SuperAdminController extends Controller
{
    private const ROLES = [
        User::ROLE_SUPERADMIN => 'Super Admin',
        User::ROLE_ADMIN      => 'Admin',
        User::ROLE_ICL        => 'ICL',
        User::ROLE_CTL        => 'CTL',
        User::ROLE_ANGGOTA    => 'Anggota',
    ];

    public function index(): View
    {
        $users = User::with('tenant')
            ->orderByRaw("CASE WHEN role = 'superadmin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('superadmin.superadmins.index', [
            'users' => $users,
            'roles' => self::ROLES,
        ]);
    }

    public function create(): View
    {
        $tenants = Tenant::orderBy('nama_perusahaan')->get();

        return view('superadmin.superadmins.create', [
            'roles'   => self::ROLES,
            'tenants' => $tenants,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'role'      => ['required', Rule::in(array_keys(self::ROLES))],
            'tenant_id' => ['required_unless:role,superadmin', 'nullable', 'exists:tenants,id'],
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'is_active' => true,
            'tenant_id' => $data['role'] === User::ROLE_SUPERADMIN ? null : $data['tenant_id'],
        ]);

        return redirect()->route('superadmin.superadmins.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function updateRole(Request $request, User $superadmin): RedirectResponse
    {
        $user = $superadmin;

        $data = $request->validate([
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
        ]);

        if ($user->id === $request->user()->id && $data['role'] !== User::ROLE_SUPERADMIN) {
            return back()->with('error', 'Anda tidak bisa menurunkan role akun sendiri.');
        }

        if (
            $user->role === User::ROLE_SUPERADMIN
            && $data['role'] !== User::ROLE_SUPERADMIN
            && User::where('role', User::ROLE_SUPERADMIN)->count() <= 1
        ) {
            return back()->with('error', 'Tidak bisa mengubah role superadmin terakhir yang tersisa.');
        }

        // Superadmin tidak terikat tenant; user lain wajib tetap punya tenant_id.
        if ($data['role'] === User::ROLE_SUPERADMIN) {
            $user->update(['role' => $data['role'], 'tenant_id' => null]);
        } elseif ($user->tenant_id === null) {
            return back()->with('error', 'Pilih tenant untuk pengguna ini lewat halaman edit sebelum mengubah role dari superadmin.');
        } else {
            $user->update(['role' => $data['role']]);
        }

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $superadmin): RedirectResponse
    {
        if ($superadmin->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if ($superadmin->role === User::ROLE_SUPERADMIN && User::where('role', User::ROLE_SUPERADMIN)->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus superadmin terakhir yang tersisa.');
        }

        $superadmin->delete();

        return redirect()->route('superadmin.superadmins.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
