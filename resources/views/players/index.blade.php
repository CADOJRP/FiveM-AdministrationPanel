@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Players</h1>
            <p class="text-gray-400">Manage all players across your servers</p>
        </div>
        
        <!-- Search -->
        <form action="{{ route('players.index') }}" method="GET" class="flex">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Search players..." 
                   class="px-4 py-2 bg-dark-800 border border-gray-700 rounded-l-lg focus:outline-none focus:border-primary-500 w-64">
            <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-r-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>
    
    <!-- Players Table -->
    <div class="glass rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-dark-800">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Player</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Trust Score</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Playtime</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Warnings</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Bans</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Last Seen</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($players as $player)
                <tr class="hover:bg-dark-800/50 transition">
                    <td class="px-6 py-4">
                        <a href="{{ route('players.show', $player) }}" class="font-medium hover:text-primary-400">
                            {{ $player->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $player->trust_score >= 75 ? 'bg-green-500/20 text-green-400' : ($player->trust_score >= 50 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                            {{ $player->trust_score }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        {{ $player->playtime_formatted }}
                    </td>
                    <td class="px-6 py-4">
                        @if($player->warnings_count > 0)
                        <span class="text-yellow-400">{{ $player->warnings_count }}</span>
                        @else
                        <span class="text-gray-500">0</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($player->bans_count > 0)
                        <span class="text-red-400">{{ $player->bans_count }}</span>
                        @else
                        <span class="text-gray-500">0</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">
                        {{ $player->last_seen_at?->diffForHumans() ?? 'Never' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('players.show', $player) }}" 
                           class="px-3 py-1 text-sm bg-primary-600 hover:bg-primary-700 rounded-lg">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        No players found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($players->hasPages())
    <div class="flex justify-center">
        {{ $players->links() }}
    </div>
    @endif
</div>
@endsection
