<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Ban;
use App\Models\Server;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_players' => Player::count(),
            'online_players' => Server::where('online', true)->sum('player_count'),
            'active_bans' => Ban::where('active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->count(),
            'servers_online' => Server::where('online', true)->count(),
            'servers_total' => Server::count(),
        ];

        $servers = Server::orderBy('name')->get();
        
        $recentPlayers = Player::orderBy('last_seen_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'servers', 'recentPlayers'));
    }
}
