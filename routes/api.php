<?php

use App\Http\Controllers\Api\FiveMController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// FiveM Server API (token authenticated)
Route::prefix('fivem')->middleware('throttle:120,1')->group(function () {
    // Server authentication
    Route::post('/auth', [FiveMController::class, 'authenticate']);
    
    // Server heartbeat
    Route::post('/heartbeat', [FiveMController::class, 'heartbeat']);
    
    // Ban checking
    Route::post('/check-ban', [FiveMController::class, 'checkBan']);
    
    // Player events
    Route::post('/player/connect', [FiveMController::class, 'playerConnect']);
    Route::post('/player/disconnect', [FiveMController::class, 'playerDisconnect']);
    
    // Player info lookup
    Route::get('/player', [FiveMController::class, 'getPlayer']);
});

// Public API for Discord bot
Route::prefix('v1')->middleware('throttle:60,1')->group(function () {
    // Player lookup (for Discord bot)
    Route::get('/players/lookup', function (\Illuminate\Http\Request $request) {
        $query = $request->input('q');
        $botToken = $request->header('X-Bot-Token');
        
        if ($botToken !== config('services.discord.bot_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $player = null;
        
        // Try to find by identifier
        if (str_contains($query, ':')) {
            [$type, $value] = explode(':', $query, 2);
            $identifier = \App\Models\PlayerIdentifier::where('type', $type)
                ->where('value', $query)
                ->first();
            if ($identifier) {
                $player = $identifier->player;
            }
        }
        
        // Try to find by name
        if (!$player) {
            $player = \App\Models\Player::where('name', 'like', "%{$query}%")->first();
        }
        
        if (!$player) {
            return response()->json(['found' => false]);
        }
        
        return response()->json([
            'found' => true,
            'player' => [
                'id' => $player->id,
                'name' => $player->name,
                'trust_score' => $player->calculateTrustScore(),
                'playtime_formatted' => $player->playtime_formatted,
                'first_joined_at' => $player->first_joined_at?->format('M d, Y'),
                'last_seen_at' => $player->last_seen_at?->format('M d, Y g:i A'),
                'warnings_count' => $player->warnings()->count(),
                'kicks_count' => $player->kicks()->count(),
                'bans_count' => $player->bans()->count(),
                'commendations_count' => $player->commendations()->count(),
                'is_banned' => $player->isBanned(),
            ],
        ]);
    });
    
    // Get player profile by Discord ID (for /profile command)
    Route::get('/players/by-discord/{discordId}', function (string $discordId, \Illuminate\Http\Request $request) {
        $botToken = $request->header('X-Bot-Token');
        
        if ($botToken !== config('services.discord.bot_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $identifier = \App\Models\PlayerIdentifier::where('type', 'discord')
            ->where('value', "discord:{$discordId}")
            ->first();
        
        if (!$identifier) {
            return response()->json(['found' => false]);
        }
        
        $player = $identifier->player;
        
        return response()->json([
            'found' => true,
            'player' => [
                'id' => $player->id,
                'name' => $player->name,
                'trust_score' => $player->calculateTrustScore(),
                'playtime_formatted' => $player->playtime_formatted,
                'first_joined_at' => $player->first_joined_at?->format('M d, Y'),
                'last_seen_at' => $player->last_seen_at?->format('M d, Y g:i A'),
                'warnings_count' => $player->warnings()->count(),
                'kicks_count' => $player->kicks()->count(),
                'bans_count' => $player->bans()->count(),
                'commendations_count' => $player->commendations()->count(),
                'is_banned' => $player->isBanned(),
            ],
        ]);
    });
});
