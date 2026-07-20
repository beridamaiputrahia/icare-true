<?php

namespace App\Http\Middleware;

use App\Models\DailyVerse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyVersePopupMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            $popupKey = 'verse_popup_shown_' . $user->id . '_' . today()->toDateString();

            if (!session()->has($popupKey)) {
                $verse = DailyVerse::getToday();

                if ($verse) {
                    session()->flash('show_daily_verse_popup', true);
                    session()->flash('daily_verse_ayat', $verse->ayat);
                    session()->flash('daily_verse_referensi', $verse->referensi);
                    session()->flash('daily_verse_renungan', $verse->renungan_singkat);
                }

                session()->put($popupKey, true);
            }
        }

        return $next($request);
    }
}
