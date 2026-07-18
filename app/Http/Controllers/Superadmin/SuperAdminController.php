<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * Kelola akun superadmin lain. Superadmin tidak terikat tenant manapun
 * (tenant_id null) — lihat App\Models\Concerns\BelongsToTenant.
 */
class SuperAdminController extends Controller
{
    public function index(): View
    {
        $superadmins = User::where('role', User::ROLE_SUPERADMIN)
            ->orderBy('name')
            ->get();

        return view('superadmin.superadmins.index', compact('superadmins'));
    }

    public function create(): View
    {
        return view('superadmin.superadmins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => User::ROLE_SUPERADMIN,
            'is_active' => true,
            'tenant_id' => null,
        ]);

        return redirect()->route('superadmin.superadmins.index')
            ->with('success', 'Superadmin baru berhasil ditambahkan.');
    }

    public function destroy(Request $request, User $superadmin): RedirectResponse
    {
        abort_unless($superadmin->role === User::ROLE_SUPERADMIN, 404);

        if ($superadmin->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if (User::where('role', User::ROLE_SUPERADMIN)->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus superadmin terakhir yang tersisa.');
        }

        $superadmin->delete();

        return redirect()->route('superadmin.superadmins.index')
            ->with('success', 'Superadmin berhasil dihapus.');
    }
}
