<?php

namespace App\Http\Middleware;

use App\Models\Photo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewUploadPopupMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            $popupKey = 'upload_popup_shown_' . $user->id;

            if (!session()->has($popupKey)) {
                $query = Photo::with('album')->latest();

                if ($user->uploads_seen_at) {
                    $query->where('created_at', '>', $user->uploads_seen_at);
                }

                $latestPhoto = $query->first();

                if ($latestPhoto && $user->uploads_seen_at) {
                    $newCount = Photo::where('created_at', '>', $user->uploads_seen_at)->count();

                    session()->flash('show_upload_popup', true);
                    session()->flash('upload_popup_count', $newCount);
                    session()->flash('upload_popup_album', $latestPhoto->album?->judul);
                }

                $user->update(['uploads_seen_at' => now()]);
                session()->put($popupKey, true);
            }
        }

        return $next($request);
    }
}
