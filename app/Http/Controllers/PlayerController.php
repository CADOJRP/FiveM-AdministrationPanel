<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Ban;
use App\Models\Kick;
use App\Models\Warning;
use App\Models\Commendation;
use App\Models\AuditLog;
use App\Events\PlayerAction;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PlayerController extends Controller
{
    /**
     * List all players
     */
    public function index(Request $request): View
    {
        $query = Player::with('identifiers')
            ->withCount(['bans', 'kicks', 'warnings', 'commendations']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('identifiers', function ($q) use ($search) {
                      $q->where('value', 'like', "%{$search}%");
                  });
            });
        }

        // Sort (whitelist allowed columns to prevent SQL injection)
        $allowedSorts = ['name', 'trust_score', 'playtime_minutes', 'last_seen_at', 'created_at'];
        $sortBy = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'last_seen_at';
        $sortDir = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $players = $query->paginate(25);

        return view('players.index', compact('players'));
    }

    /**
     * Show player profile
     */
    public function show(Player $player): View
    {
        $player->load([
            'identifiers',
            'bans' => fn($q) => $q->latest()->take(10),
            'kicks' => fn($q) => $q->latest()->take(10),
            'warnings' => fn($q) => $q->latest()->take(10),
            'commendations' => fn($q) => $q->latest()->take(10),
        ]);

        // Recalculate trust score
        $player->trust_score = $player->calculateTrustScore();
        $player->save();

        return view('players.show', compact('player'));
    }

    /**
     * Ban a player
     */
    public function ban(Request $request, Player $player): JsonResponse
    {
        if (!auth()->user()->hasPermission('ban')) {
            abort(403, 'You do not have permission to ban players.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:0', // Minutes, 0 = permanent
        ]);

        $staff = auth()->user();
        $expiresAt = $validated['duration'] ? now()->addMinutes($validated['duration']) : null;

        // Get all player identifiers
        $identifiers = $player->identifiers->pluck('value', 'type')->toArray();

        $ban = Ban::create([
            'player_id' => $player->id,
            'identifiers' => $identifiers,
            'player_name' => $player->name,
            'reason' => $validated['reason'],
            'expires_at' => $expiresAt,
            'banned_by_discord_id' => $staff->discord_id,
            'banned_by_name' => $staff->discord_username,
            'active' => true,
        ]);

        AuditLog::logAction('ban', $player->id, $staff->discord_id, $staff->discord_username, [
            'reason' => $validated['reason'],
            'duration' => $validated['duration'] ?? null,
            'ban_id' => $ban->id,
        ]);

        // Broadcast to FiveM servers to kick player
        broadcast(new PlayerAction('kick', $player, [
            'reason' => "Banned: " . $validated['reason'],
        ]));

        // Send Discord notification
        $durationText = $validated['duration'] ? ($validated['duration'] . ' minutes') : 'Permanent';
        DiscordWebhookService::sendBan($player->name, $validated['reason'], $staff->discord_username, $durationText);

        return response()->json([
            'success' => true,
            'message' => 'Player has been banned.',
            'ban' => $ban,
        ]);
    }

    /**
     * Unban a player
     */
    public function unban(Request $request, Ban $ban): JsonResponse
    {
        if (!auth()->user()->hasPermission('unban')) {
            abort(403, 'You do not have permission to unban players.');
        }

        $staff = auth()->user();

        $ban->update(['active' => false]);

        AuditLog::logAction('unban', $ban->player_id, $staff->discord_id, $staff->discord_username, [
            'ban_id' => $ban->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ban has been removed.',
        ]);
    }

    /**
     * Kick a player
     */
    public function kick(Request $request, Player $player): JsonResponse
    {
        if (!auth()->user()->hasPermission('kick')) {
            abort(403, 'You do not have permission to kick players.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $staff = auth()->user();

        $kick = Kick::create([
            'player_id' => $player->id,
            'player_name' => $player->name,
            'reason' => $validated['reason'],
            'kicked_by_discord_id' => $staff->discord_id,
            'kicked_by_name' => $staff->discord_username,
        ]);

        AuditLog::logAction('kick', $player->id, $staff->discord_id, $staff->discord_username, [
            'reason' => $validated['reason'],
            'kick_id' => $kick->id,
        ]);

        // Broadcast to FiveM servers
        broadcast(new PlayerAction('kick', $player, [
            'reason' => $validated['reason'],
        ]));

        // Send Discord notification
        DiscordWebhookService::sendKick($player->name, $validated['reason'], $staff->discord_username);

        return response()->json([
            'success' => true,
            'message' => 'Player has been kicked.',
        ]);
    }

    /**
     * Warn a player
     */
    public function warn(Request $request, Player $player): JsonResponse
    {
        if (!auth()->user()->hasPermission('warn')) {
            abort(403, 'You do not have permission to warn players.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $staff = auth()->user();

        $warning = Warning::create([
            'player_id' => $player->id,
            'player_name' => $player->name,
            'reason' => $validated['reason'],
            'warned_by_discord_id' => $staff->discord_id,
            'warned_by_name' => $staff->discord_username,
        ]);

        AuditLog::logAction('warn', $player->id, $staff->discord_id, $staff->discord_username, [
            'reason' => $validated['reason'],
            'warning_id' => $warning->id,
        ]);

        // Broadcast notification to player
        broadcast(new PlayerAction('warn', $player, [
            'reason' => $validated['reason'],
        ]));

        // Send Discord notification
        DiscordWebhookService::sendWarn($player->name, $validated['reason'], $staff->discord_username);

        // Recalculate trust score
        $player->trust_score = $player->calculateTrustScore();
        $player->save();

        return response()->json([
            'success' => true,
            'message' => 'Player has been warned.',
        ]);
    }

    /**
     * Commend a player
     */
    public function commend(Request $request, Player $player): JsonResponse
    {
        if (!auth()->user()->hasPermission('commend')) {
            abort(403, 'You do not have permission to commend players.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $staff = auth()->user();

        $commendation = Commendation::create([
            'player_id' => $player->id,
            'player_name' => $player->name,
            'reason' => $validated['reason'],
            'commended_by_discord_id' => $staff->discord_id,
            'commended_by_name' => $staff->discord_username,
        ]);

        AuditLog::logAction('commend', $player->id, $staff->discord_id, $staff->discord_username, [
            'reason' => $validated['reason'],
            'commendation_id' => $commendation->id,
        ]);

        // Recalculate trust score
        $player->trust_score = $player->calculateTrustScore();
        $player->save();

        return response()->json([
            'success' => true,
            'message' => 'Player has been commended.',
        ]);
    }
}
