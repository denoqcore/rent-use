<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Events\MessageEdited;
use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use AuthorizesRequests;

     public function openOrCreate(Request $request, ?Listing $listing = null, ?User $user = null)
    {
        $otherUserId = $listing ? $listing->user_id : $user->id;

        if ($otherUserId === auth()->id()) {
            return response()->json(['error' => 'Cannot chat with yourself'], 422);
        }

        $chat = Chat::findOrCreateBetween(auth()->id(), $otherUserId);

        return response()->json(['chat' => ['id' => $chat->id]]);
    }

    public function send(Request $request, Chat $chat)
    {
        $this->authorize('participate', $chat);

        $request->validate([
            'body'       => 'required|string|max:2000',
            'listing_id' => 'nullable|exists:listings,id',
        ]);

        $message = $chat->messages()->create([
            'sender_id'  => auth()->id(),
            'body'       => $request->body,
            'listing_id' => $request->listing_id ?? null,
        ]);

        $message->load('sender', 'listing');
        $chat->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message));

        return response()->json(['ok' => true, 'message' => $this->formatMessage($message)]);
    }

    public function edit(Request $request, ChatMessage $message)
    {
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:2000']);

        $message->update([
            'body'      => $request->body,
            'edited_at' => now(),
        ]);

        broadcast(new MessageEdited($message));

        return response()->json(['ok' => true]);
    }

    public function destroy(ChatMessage $message)
    {
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        broadcast(new MessageDeleted($message));

        return response()->json(['ok' => true]);
    }

    public function markRead(Chat $chat)
    {
        $this->authorize('participate', $chat);

        $chat->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        broadcast(new MessageRead($chat->id, auth()->id()));

        return response()->json(['ok' => true]);
    }

    public function index()
    {
        $chats = Chat::where('owner_id', auth()->id())
            ->orWhere('renter_id', auth()->id())
            ->with(['owner', 'renter', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        return response()->json($chats->map(fn($chat) => $this->formatChat($chat)));
    }

    public function show(Chat $chat)
{
    $this->authorize('participate', $chat);

    $chat->messages()
        ->whereNull('read_at')
        ->where('sender_id', '!=', auth()->id())
        ->update(['read_at' => now()]);

    broadcast(new MessageRead($chat->id, auth()->id()));

    $chat->load([
        'owner',
        'renter',
        'messages' => fn ($q) => $q->withTrashed()->orderBy('created_at'),
        'messages.sender',
        'messages.listing',
    ]);
    $other = $chat->otherUser();

    return response()->json([
        'chat' => [
            'id'         => $chat->id,
            'other_user' => [
                'id'     => $other->id,
                'name'   => $other->name,
                'avatar' => $other->avatar,
            ],
        ],
        'messages' => $chat->messages->map(fn($m) => $this->formatMessage($m)),
    ]);
}

    private function formatChat(Chat $chat): array
    {
        $other = $chat->otherUser();
        return [
            'id'         => $chat->id,
            'other_user' => [
                'id'     => $other->id,
                'name'   => $other->name,
                'avatar' => $other->avatar,
            ],
            'last_message' => $chat->lastMessage ? [
                'body'       => $chat->lastMessage->trashed() ? __('messages.message-deleted') : $chat->lastMessage->body,
                'created_at' => $chat->lastMessage->created_at->format('H:i'),
                'is_mine'    => $chat->lastMessage->sender_id === auth()->id(),
            ] : null,
            'unread' => $chat->messages()
                ->whereNull('read_at')
                ->where('sender_id', '!=', auth()->id())
                ->count(),
        ];
    }

    private function formatMessage(ChatMessage $m): array
{
    return [
        'id'         => $m->id,
        'body'       => $m->trashed() ? null : $m->body,
        'sender_id'  => $m->sender_id,
        'created_at' => $m->created_at->format('H:i'),
        'edited_at'  => $m->edited_at?->format('H:i'),
        'is_mine'    => $m->sender_id === auth()->id(),
        'is_deleted' => $m->trashed(),
        'read_at'    => $m->read_at,
        'listing'    => (!$m->trashed() && $m->listing) ? [
            'id'    => $m->listing->id,
            'title' => $m->listing->title,
            'slug'  => $m->listing->slug,
        ] : null,
        'sender' => [
            'name'   => $m->sender->name,
            'avatar' => $m->sender->avatar,
        ],
    ];
}

}
