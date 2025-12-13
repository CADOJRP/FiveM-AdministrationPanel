<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'identifiers',
        'player_name',
        'reason',
        'expires_at',
        'banned_by_discord_id',
        'banned_by_name',
        'active',
    ];

    protected $casts = [
        'identifiers' => 'array',
        'expires_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Check if ban is permanent
     */
    public function isPermanent(): bool
    {
        return $this->expires_at === null;
    }

    /**
     * Check if ban is expired
     */
    public function isExpired(): bool
    {
        if ($this->isPermanent()) {
            return false;
        }
        return $this->expires_at->isPast();
    }

    /**
     * Get time remaining formatted
     */
    public function getTimeRemainingAttribute(): string
    {
        if ($this->isPermanent()) {
            return 'Permanent';
        }
        
        if ($this->isExpired()) {
            return 'Expired';
        }
        
        return $this->expires_at->diffForHumans();
    }

    /**
     * Check if any identifier from array matches this ban
     */
    public static function checkIdentifiers(array $identifiers): ?self
    {
        $bans = self::where('active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        foreach ($bans as $ban) {
            $banIdentifiers = $ban->identifiers ?? [];
            foreach ($identifiers as $type => $value) {
                if (isset($banIdentifiers[$type]) && $banIdentifiers[$type] === $value) {
                    return $ban;
                }
            }
        }

        return null;
    }
}
