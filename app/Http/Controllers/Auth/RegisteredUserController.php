<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lengkap'     => ['required', 'string', 'max:255'],
            'nama_panggilan'   => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'    => ['nullable', 'date', 'before:today'],
            'alamat'           => ['nullable', 'string', 'max:500'],
            'nomor_hp'         => ['nullable', 'string', 'max:20'],
            'covenant_aktif'   => ['nullable', 'boolean'],
            'covenant_number'  => ['nullable', 'string', 'max:50', 'unique:members,covenant_number'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'is_active'=> true,
        ]);

        $covenantAktif  = $request->boolean('covenant_aktif');
        $covenantNumber = $covenantAktif ? ($request->covenant_number ?: null) : null;

        Member::create([
            'user_id'         => $user->id,
            'nama_lengkap'    => $request->nama_lengkap,
            'nama_panggilan'  => $request->nama_panggilan,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'alamat'          => $request->alamat,
            'nomor_hp'        => $request->nomor_hp,
            'covenant_number' => $covenantNumber,
            'is_active'       => true,
            'created_by'      => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
