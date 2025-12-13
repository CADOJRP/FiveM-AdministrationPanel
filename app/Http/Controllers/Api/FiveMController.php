<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Ban;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FiveMController extends Controller
{
    /**
     * Server authentication
     */
    public function authenticate(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        $server = Server::findByToken($token);

        if (!$server) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Update heartbeat
        $server->heartbeat();

        return response()->json([
            'success' => true,
            'server_id' => $server->id,
            'server_name' => $server->name,
        ]);
    }

    /**
     * Server heartbeat
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $server = $this->getAuthenticatedServer($request);

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $server->heartbeat($request->input('player_count', 0));

        return response()->json(['success' => true]);
    }

    /**
     * Check if player is banned
     */
    public function checkBan(Request $request): JsonResponse
    {
        $server = $this->getAuthenticatedServer($request);

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $identifiers = $request->input('identifiers', []);

        if (empty($identifiers)) {
            return response()->json(['error' => 'No identifiers provided'], 400);
        }

        $ban = Ban::checkIdentifiers($identifiers);

        if ($ban) {
            return response()->json([
                'banned' => true,
                'reason' => $ban->reason,
                'expires_at' => $ban->expires_at?->toIso8601String(),
                'banned_by' => $ban->banned_by_name,
                'banned_at' => $ban->created_at->toIso8601String(),
                'permanent' => $ban->isPermanent(),
            ]);
        }

        return response()->json(['banned' => false]);
    }

    /**
     * Player connected to server
     */
    public function playerConnect(Request $request): JsonResponse
    {
        $server = $this->getAuthenticatedServer($request);

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifiers' => 'required|array',
            'identifiers.fivem' => 'nullable|string',
            'identifiers.discord' => 'nullable|string',
            'identifiers.license2' => 'nullable|string',
            'identifiers.license' => 'nullable|string',
            'identifiers.steam' => 'nullable|string',
            'identifiers.xbl' => 'nullable|string',
            'identifiers.ip' => 'nullable|string',
        ]);

        // Filter out null/empty identifiers
        $identifiers = array_filter($validated['identifiers']);

        // Find or create player
        $player = Player::findOrCreateByIdentifiers($identifiers, $validated['name']);

        // Update playtime
        $player->increment('playtime_minutes', 1);

        return response()->json([
            'success' => true,
            'player_id' => $player->id,
            'trust_score' => $player->calculateTrustScore(),
        ]);
    }

    /**
     * Player disconnected
     */
    public function playerDisconnect(Request $request): JsonResponse
    {
        $server = $this->getAuthenticatedServer($request);

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $identifiers = $request->input('identifiers', []);

        // Find player and update last seen
        foreach ($identifiers as $type => $value) {
            $identifier = \App\Models\PlayerIdentifier::where('type', $type)
                ->where('value', $value)
                ->first();

            if ($identifier) {
                $identifier->player->update(['last_seen_at' => now()]);
                break;
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get player info by identifier
     */
    public function getPlayer(Request $request): JsonResponse
    {
        $server = $this->getAuthenticatedServer($request);

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $type = $request->input('type');
        $value = $request->input('value');

        if (!$type || !$value) {
            return response()->json(['error' => 'Type and value required'], 400);
        }

        $identifier = \App\Models\PlayerIdentifier::where('type', $type)
            ->where('value', $value)
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
                'playtime_minutes' => $player->playtime_minutes,
                'first_joined_at' => $player->first_joined_at?->toIso8601String(),
                'last_seen_at' => $player->last_seen_at?->toIso8601String(),
                'warnings_count' => $player->warnings()->count(),
                'kicks_count' => $player->kicks()->count(),
                'bans_count' => $player->bans()->count(),
                'commendations_count' => $player->commendations()->count(),
                'is_banned' => $player->isBanned(),
            ],
        ]);
    }

    /**
     * Get authenticated server from request
     */
    private function getAuthenticatedServer(Request $request): ?Server
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        return Server::findByToken($token);
    }
}
