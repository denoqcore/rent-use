<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
        $this->message->load('chat', 'sender');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->chat_id),
            new PrivateChannel('user.' . $this->message->chat->owner_id),
            new PrivateChannel('user.' . $this->message->chat->renter_id),
        ];
    }

    public function broadcastWith(): array
{
    return [
        'id'         => $this->message->id,
        'chat_id'    => $this->message->chat_id,
        'body'       => $this->message->body,
        'sender_id'  => $this->message->sender_id,
        'created_at' => $this->message->created_at->format('H:i'),
        'edited_at'  => null,
        'listing'    => $this->message->listing_id ? [
            'id'    => $this->message->listing->id,
            'title' => $this->message->listing->title,
            'slug'  => $this->message->listing->slug,
        ] : null,
        'sender' => [
            'id'     => $this->message->sender->id,
            'name'   => $this->message->sender->name,
            'avatar' => $this->message->sender->avatar,
        ],
    ];
}
}
