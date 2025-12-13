<?php

namespace App\Events;

use App\Models\Player;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerAction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;
    public array $playerData;
    public array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(string $action, Player $player, array $data = [])
    {
        $this->action = $action;
        $this->playerData = [
            'id' => $player->id,
            'name' => $player->name,
            'identifiers' => $player->identifiers->pluck('value', 'type')->toArray(),
        ];
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('fivem-actions'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'player.action';
    }
}
