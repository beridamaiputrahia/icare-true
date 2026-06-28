<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user   = $request->user()->load('member');
        $member = $user->member;

        return view('profile.edit', compact('user', 'member'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'  => ['nullable', 'date', 'before:today'],
            'alamat'         => ['nullable', 'string', 'max:500'],
            'nomor_hp'       => ['nullable', 'string', 'max:20'],
            'covenant_aktif' => ['nullable', 'boolean'],
            'covenant_number'=> ['nullable', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('members', 'covenant_number')
                    ->ignore($user->member?->id),
            ],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
            ],
            'foto'           => ['nullable', 'image', 'max:2048'],
        ]);

        // Update User
        if ($user->email !== $data['email']) {
            $user->email_verified_at = null;
        }
        $user->name  = $data['nama_lengkap'];
        $user->email = $data['email'];
        $user->save();

        // Covenant
        $covenantAktif  = $request->boolean('covenant_aktif');
        $covenantNumber = $covenantAktif ? ($request->covenant_number ?: null) : null;

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            if ($user->member?->foto) {
                Storage::disk('public')->delete($user->member->foto);
            }
            $fotoPath = $request->file('foto')->store('members', 'public');
        }

        // Update or create Member
        $memberData = [
            'nama_lengkap'   => $data['nama_lengkap'],
            'nama_panggilan' => $data['nama_panggilan'],
            'tanggal_lahir'  => $data['tanggal_lahir'],
            'alamat'         => $data['alamat'],
            'nomor_hp'       => $data['nomor_hp'],
            'covenant_number'=> $covenantNumber,
        ];
        if ($fotoPath) {
            $memberData['foto'] = $fotoPath;
        }

        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge($memberData, ['created_by' => $user->id, 'is_active' => true])
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
