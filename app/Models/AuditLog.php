<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'player_id',
        'staff_discord_id',
        'staff_name',
        'details',
        'ip_address',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Log an action
     */
    public static function logAction(
        string $action,
        ?int $playerId,
        ?string $staffDiscordId,
        ?string $staffName,
        array $details = [],
        ?string $ipAddress = null
    ): self {
        return self::create([
            'action' => $action,
            'player_id' => $playerId,
            'staff_discord_id' => $staffDiscordId,
            'staff_name' => $staffName,
            'details' => $details,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }
}
