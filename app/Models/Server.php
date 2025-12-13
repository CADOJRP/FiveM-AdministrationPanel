<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'connection',
        'online',
        'player_count',
        'last_heartbeat_at',
    ];

    protected $casts = [
        'online' => 'boolean',
        'last_heartbeat_at' => 'datetime',
    ];

    // Note: Token is NOT hidden so admins can view/copy it in the servers page
    // API responses that include servers should manually exclude the token

    /**
     * Generate a new token for the server
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if server is considered online (heartbeat within last 2 minutes)
     */
    public function isOnline(): bool
    {
        if (!$this->last_heartbeat_at) {
            return false;
        }
        
        return $this->last_heartbeat_at->isAfter(now()->subMinutes(2));
    }

    /**
     * Update heartbeat
     */
    public function heartbeat(int $playerCount = 0): void
    {
        $this->update([
            'online' => true,
            'player_count' => $playerCount,
            'last_heartbeat_at' => now(),
        ]);
    }

    /**
     * Find server by token
     */
    public static function findByToken(string $token): ?self
    {
        return self::where('token', $token)->first();
    }
}
