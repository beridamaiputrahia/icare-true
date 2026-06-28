<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Global conversation (everyone)
        $global = Conversation::find(Conversation::GLOBAL_ID);

        // Leader conversation (admin only)
        $leader = $user->isAdmin()
            ? Conversation::find(Conversation::LEADER_ID)
            : null;

        // Private conversations
        $privates = $user->conversations()
            ->where('type', 'private')
            ->with('participants')
            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('user_id', '!=', $user->id)
                  ->whereNotIn('id', function ($sub) use ($user) {
                      $sub->select('message_id')
                          ->from('message_reads')
                          ->where('user_id', $user->id);
                  });
            }])
            ->get();

        // Ensure user is participant in global
        if ($global) {
            $global->participants()->syncWithoutDetaching([$user->id]);
        }

        $activeConvId = request('conv', $global?->id ?? 0);
        $activeConv   = Conversation::find($activeConvId);
        $messages     = [];

        if ($activeConv) {
            if ($activeConv->type === 'leader' && !$user->isAdmin()) {
                abort(403);
            }
            if ($activeConv->type === 'private' &&
                !$activeConv->participants()->where('user_id', $user->id)->exists()) {
                abort(403);
            }
            if ($activeConv->type !== 'private') {
                $activeConv->participants()->syncWithoutDetaching([$user->id]);
            }
            $activeConv->markReadFor($user);

            $messages = $activeConv->messages()
                ->with('user')
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->values();
        }

        $onlineUsers = User::where('is_online', true)->where('id', '!=', $user->id)->get();

        return view('chat.index', compact(
            'global', 'leader', 'privates', 'activeConv', 'messages', 'onlineUsers'
        ));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body'            => 'required|string|max:2000',
        ]);

        $user = auth()->user();
        $conv = Conversation::findOrFail($data['conversation_id']);

        // Auth check
        if ($conv->type === 'leader' && !$user->isAdmin()) {
            abort(403);
        }
        if ($conv->type === 'private') {
            abort_unless(
                $conv->participants()->where('user_id', $user->id)->exists(),
                403
            );
        }

        $message = Message::create([
            'conversation_id' => $conv->id,
            'user_id'         => $user->id,
            'body'            => $data['body'],
        ]);

        $message->load(['user', 'conversation']);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => [
                'id'               => $message->id,
                'body'             => $message->body,
                'user_id'          => $user->id,
                'user_name'        => $user->name,
                'created_at_human' => $message->created_at->diffForHumans(),
            ],
        ]);
    }

    public function typing(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'is_typing'       => 'required|boolean',
        ]);

        broadcast(new UserTyping(auth()->user(), $data['conversation_id'], $data['is_typing']))->toOthers();

        return response()->json(['success' => true]);
    }

    public function startPrivate(User $user)
    {
        $me = auth()->user();

        // Find or create private conversation
        $existing = Conversation::where('type', 'private')
            ->whereHas('participants', fn ($q) => $q->where('user_id', $me->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$existing) {
            $existing = Conversation::create(['type' => 'private']);
            $existing->participants()->attach([$me->id, $user->id]);
        }

        return redirect()->route('chat.index', ['conv' => $existing->id]);
    }

    public function deleteMessage(Message $message)
    {
        $this->authorize('delete', $message);

        $message->update(['is_deleted' => true, 'body' => '']);

        return response()->json(['success' => true]);
    }

    public function loadMore(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'before_id'       => 'required|integer',
        ]);

        $user = auth()->user();
        $conv = Conversation::findOrFail($data['conversation_id']);

        if ($conv->type === 'leader' && !$user->isAdmin()) abort(403);

        $messages = $conv->messages()
            ->with('user')
            ->where('id', '<', $data['before_id'])
            ->latest()
            ->take(30)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'messages' => $messages->map(fn ($m) => [
                'id'               => $m->id,
                'user_id'          => $m->user_id,
                'user_name'        => $m->user->name,
                'body'             => $m->display_body,
                'is_deleted'       => $m->is_deleted,
                'created_at_human' => $m->created_at->diffForHumans(),
            ]),
            'has_more' => $conv->messages()->where('id', '<', $messages->first()?->id ?? 0)->exists(),
        ]);
    }
}
