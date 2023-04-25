<?php

namespace App\Events\User;

use App\Models\AuthenticationLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;

class SuspiciousLoginAttempt
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Authenticatable $user;
    public array $data;
    public AuthenticationLog $log;

    /**
     * Create a new event instance.
     * @param Authenticatable $user
     * @param array $data
     */
    public function __construct(Authenticatable $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
        $agent = new Agent();
        $agent->setUserAgent($this->data['user_agent']);
        $this->data['device'] = $agent->platform() ?? '' . $agent->device() ?? '' . $agent->browser() ?? '';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
