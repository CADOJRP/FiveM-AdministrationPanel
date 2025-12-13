<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trust_score',
        'playtime_minutes',
        'first_joined_at',
        'last_seen_at',
    ];

    protected $casts = [
        'first_joined_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    /**
     * Get formatted playtime
     */
    public function getPlaytimeFormattedAttribute(): string
    {
        $minutes = $this->playtime_minutes ?? 0;
        
        if ($minutes < 60) {
            return "{$minutes}m";
        }
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours < 24) {
            return "{$hours}h {$mins}m";
        }
        
        $days = floor($hours / 24);
        $hrs = $hours % 24;
        
        return "{$days}d {$hrs}h";
    }

    /**
     * Get player identifiers
     */
    public function identifiers(): HasMany
    {
        return $this->hasMany(PlayerIdentifier::class);
    }

    /**
     * Get player bans
     */
    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    /**
     * Get player kicks
     */
    public function kicks(): HasMany
    {
        return $this->hasMany(Kick::class);
    }

    /**
     * Get player warnings
     */
    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class);
    }

    /**
     * Get player commendations
     */
    public function commendations(): HasMany
    {
        return $this->hasMany(Commendation::class);
    }

    /**
     * Check if player is currently banned
     */
    public function isBanned(): bool
    {
        return $this->bans()
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Get active ban
     */
    public function getActiveBan(): ?Ban
    {
        return $this->bans()
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    /**
     * Calculate trust score
     */
    public function calculateTrustScore(): int
    {
        $score = config('panel.trust_score.base', 75);
        
        // Add playtime bonus (1 point per hour, max 25)
        $hoursPlayed = floor($this->playtime_minutes / 60);
        $score += min($hoursPlayed, 25);
        
        // Subtract for warnings
        $score -= $this->warnings()->count() * config('panel.trust_score.warn_penalty', 3);
        
        // Subtract for kicks
        $score -= $this->kicks()->count() * config('panel.trust_score.kick_penalty', 6);
        
        // Subtract for bans
        $score -= $this->bans()->count() * config('panel.trust_score.ban_penalty', 10);
        
        // Add for commendations
        $score += $this->commendations()->count() * config('panel.trust_score.commend_bonus', 2);
        
        // Clamp between 0 and 100
        return max(0, min(100, $score));
    }

    /**
     * Find or create player by identifiers
     */
    public static function findOrCreateByIdentifiers(array $identifiers, string $name): self
    {
        // Try to find existing player by any identifier
        foreach ($identifiers as $type => $value) {
            $existingIdentifier = PlayerIdentifier::where('type', $type)
                ->where('value', $value)
                ->first();
            
            if ($existingIdentifier) {
                $player = $existingIdentifier->player;
                $player->update([
                    'name' => $name,
                    'last_seen_at' => now(),
                ]);
                
                // Update all identifiers
                foreach ($identifiers as $t => $v) {
                    PlayerIdentifier::updateOrCreate(
                        ['type' => $t, 'value' => $v],
                        ['player_id' => $player->id, 'last_seen_at' => now()]
                    );
                }
                
                return $player;
            }
        }
        
        // Create new player
        $player = self::create([
            'name' => $name,
            'first_joined_at' => now(),
            'last_seen_at' => now(),
        ]);
        
        // Create identifiers
        foreach ($identifiers as $type => $value) {
            PlayerIdentifier::create([
                'player_id' => $player->id,
                'type' => $type,
                'value' => $value,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
            ]);
        }
        
        return $player;
    }
}
