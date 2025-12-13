@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-gray-400">Welcome back, {{ auth()->user()->discord_username }}</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Online Players</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['online_players'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="glass rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Players</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_players']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="glass rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Active Bans</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['active_bans'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="glass rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Servers Online</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['servers_online'] }}/{{ $stats['servers_total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Servers & Recent Players -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Servers -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">Servers</h2>
            <div class="space-y-4">
                @forelse($servers as $server)
                <div class="flex items-center justify-between p-4 bg-dark-800 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full {{ $server->isOnline() ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                        <div>
                            <p class="font-medium">{{ $server->name }}</p>
                            <p class="text-sm text-gray-400">{{ $server->connection ?? 'No connection info' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">{{ $server->player_count }} players</p>
                        <p class="text-xs text-gray-400">{{ $server->isOnline() ? 'Online' : 'Offline' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-center py-8">No servers configured</p>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Players -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Players</h2>
            <div class="space-y-3">
                @forelse($recentPlayers as $player)
                <a href="{{ route('players.show', $player) }}" 
                   class="flex items-center justify-between p-3 bg-dark-800 rounded-lg hover:bg-dark-700 transition">
                    <div>
                        <p class="font-medium">{{ $player->name }}</p>
                        <p class="text-xs text-gray-400">Last seen {{ $player->last_seen_at?->diffForHumans() ?? 'Never' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full {{ $player->trust_score >= 75 ? 'bg-green-500/20 text-green-400' : ($player->trust_score >= 50 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                            {{ $player->trust_score }}% Trust
                        </span>
                    </div>
                </a>
                @empty
                <p class="text-gray-400 text-center py-8">No players yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
