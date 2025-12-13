<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'player_name',
        'reason',
        'commended_by_discord_id',
        'commended_by_name',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
