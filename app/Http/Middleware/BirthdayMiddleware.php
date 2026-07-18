<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BirthdayMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Default: no birthday theme
        view()->share('_isBirthday', false);
        view()->share('_birthdayName', null);
        view()->share('_birthdayMembers', collect());

        if (auth()->check()) {
            $user = auth()->user();

            // --- OWN BIRTHDAY: check the logged-in user's own member record ---
            $ownMember = $user->member ?? $user->memberByName;

            if ($ownMember && $ownMember->tanggal_lahir) {
                $dob = $ownMember->tanggal_lahir;

                if ($dob->month === today()->month && $dob->day === today()->day) {
                    $name = $ownMember->nama_panggilan ?: $ownMember->nama_lengkap ?: $user->name;

                    // Birthday THEME: active ALL DAY on every request
                    view()->share('_isBirthday', true);
                    view()->share('_birthdayName', $name);

                    // Birthday POPUP: once per session (resets when session expires = new app open)
                    $popupKey = 'bday_popup_shown_' . $user->id;
                    if (!session()->has($popupKey)) {
                        session()->flash('show_birthday_popup', true);
                        session()->put($popupKey, true);
                    }

                    return $next($request);
                }
            }

            // --- ADMIN VIEW: show today's birthday members as banner (not full theme) ---
            if ($user->isAdmin()) {
                $todayMembers = Member::whereNotNull('tanggal_lahir')
                    ->whereRaw("EXTRACT(MONTH FROM tanggal_lahir) = ?", [today()->month])
                    ->whereRaw("EXTRACT(DAY FROM tanggal_lahir) = ?", [today()->day])
                    ->where('is_active', true)
                    ->get(['id', 'nama_lengkap', 'nama_panggilan']);

                if ($todayMembers->isNotEmpty()) {
                    view()->share('_birthdayMembers', $todayMembers);
                }
            }
        }

        return $next($request);
    }
}
