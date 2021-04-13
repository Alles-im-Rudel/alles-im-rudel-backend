<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BirthdayChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected User $user;

	/**
	 * Create a new event instance.
	 *
	 * @param  User  $user
	 */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
