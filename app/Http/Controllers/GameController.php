<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $members = \App\Models\User::select('id', 'name', 'role')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get()
            ->map(fn($u) => [
                'id'   => $u->id,
                'nama' => $u->name,
                'role' => $u->role ?? 'anggota',
            ]);

        return view('game.index', compact('user', 'members'));
    }

    public function serveJsx()
    {
        $path = public_path('js/game/GameFeature.jsx');
        abort_unless(file_exists($path), 404);
        return response(file_get_contents($path), 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
