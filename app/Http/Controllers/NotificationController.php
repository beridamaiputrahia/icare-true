<?php

namespace App\Http\Controllers;

use App\Managers\NotificationManager;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationManager $manager) {}

    public function index()
    {
        $notifications = $this->manager->getForUser(auth()->user(), 20);
        $this->manager->markAllRead(auth()->user());
        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => $this->manager->unreadCount(auth()->user()),
        ]);
    }

    public function markRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        $this->manager->markAllRead(auth()->user());
        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }

    public function destroy(string $id)
    {
        auth()->user()->notifications()->where('id', $id)->delete();
        return response()->json(['ok' => true]);
    }
}
