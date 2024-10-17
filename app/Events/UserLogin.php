<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLogin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $connection = 'rabbitmq';
    public $queue = 'broadcast';

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected User $user
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('base'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.login';
    }

    public function broadcastWith(): array
    {
        return ['phone' => $this->user->phone];
    }
}
