<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Listing;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use AuthorizesRequests;

    public function openOrCreate(Listing $listing)
    {
        $chat = Chat::firstOrCreate(
            [
                'listing_id' => $listing->id,
                'renter_id'  => auth()->id(),
            ],
            [
                'owner_id' => $listing->user_id,
            ]
        );

        $messages = $chat->messages()->with('sender')->get();

        return response()->json([
            'chat'     => $chat,
            'messages' => $messages->map(fn($m) => [
                'id'        => $m->id,
                'body'      => $m->body,
                'sender_id' => $m->sender_id,
                'created_at'=> $m->created_at->format('H:i'),
                'sender'    => [
                    'id'     => $m->sender->id,
                    'name'   => $m->sender->name,
                    'avatar' => $m->sender->avatar,
                ],
            ]),
        ]);
    }

    public function send(Request $request, Chat $chat)
    {
        $this->authorize('participate', $chat);

        $request->validate(['body' => 'required|string|max:2000']);

        $message = $chat->messages()->create([
            'sender_id' => auth()->id(),
            'body'      => $request->body,
        ]);

        $message->load('sender');

        $chat->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message));

        return response()->json(['ok' => true]);
    }

public function index()
{
    $chats = Chat::where('owner_id', auth()->id())
        ->orWhere('renter_id', auth()->id())
        ->with(['listing', 'owner', 'renter', 'lastMessage'])
        ->orderByDesc('last_message_at')
        ->get();

    return response()->json($chats->map(function ($chat) {
        $other = $chat->otherUser();

        return [
            'id'      => $chat->id,
            'listing' => [
                'id'    => $chat->listing->id,
                'title' => $chat->listing->title,
                'image' => $chat->listing->images()->first()?->path,
            ],
            'other_user' => [
                'id'     => $other->id,
                'name'   => $other->name,
                'avatar' => $other->avatar,
            ],
            'last_message' => $chat->lastMessage ? [
                'body'       => $chat->lastMessage->body,
                'created_at' => $chat->lastMessage->created_at->format('H:i'),
                'is_mine'    => $chat->lastMessage->sender_id === auth()->id(),
            ] : null,
            'unread' => $chat->messages()
                ->whereNull('read_at')
                ->where('sender_id', '!=', auth()->id())
                ->count(),
        ];
    }));
}

public function show(Chat $chat)
{
    $this->authorize('participate', $chat);

    $chat->messages()
        ->whereNull('read_at')
        ->where('sender_id', '!=', auth()->id())
        ->update(['read_at' => now()]);

    $chat->load(['owner', 'renter', 'listing', 'messages.sender']);

    $other = $chat->otherUser();

    return response()->json([
        'chat' => [
            'id'         => $chat->id,
            'other_user' => [
                'id'     => $other->id,
                'name'   => $other->name,
                'avatar' => $other->avatar,
            ],
            'listing' => [
                'title' => $chat->listing->title,
            ],
        ],
        'messages' => $chat->messages->map(fn($m) => [
            'id'         => $m->id,
            'body'       => $m->body,
            'sender_id'  => $m->sender_id,
            'created_at' => $m->created_at->format('H:i'),
            'is_mine'    => $m->sender_id === auth()->id(),
            'sender'     => [
                'name'   => $m->sender->name,
                'avatar' => $m->sender->avatar,
            ],
        ]),
    ]);
}
}
