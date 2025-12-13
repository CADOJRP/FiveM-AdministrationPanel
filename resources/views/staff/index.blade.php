@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold">Staff</h1>
        <p class="text-gray-400">View staff members and their activity</p>
    </div>
    
    <!-- Staff Table -->
    <div class="glass rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-dark-800">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Staff Member</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Role</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Bans</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Kicks</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Warnings</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Last Login</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($staff as $member)
                <tr class="hover:bg-dark-800/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <img src="{{ $member->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <p class="font-medium">{{ $member->discord_username }}</p>
                                <p class="text-xs text-gray-500">{{ $member->discord_id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full 
                            @if($member->role === 'owner') bg-purple-500/20 text-purple-400
                            @elseif($member->role === 'admin') bg-red-500/20 text-red-400
                            @elseif($member->role === 'moderator') bg-blue-500/20 text-blue-400
                            @else bg-gray-500/20 text-gray-400
                            @endif">
                            {{ ucfirst($member->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-red-400 font-semibold">{{ $member->bans_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-orange-400 font-semibold">{{ $member->kicks_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-yellow-400 font-semibold">{{ $member->warnings_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">
                        {{ $member->last_login_at?->diffForHumans() ?? 'Never' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        No staff members found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Info Card -->
    <div class="glass rounded-xl p-6">
        <h3 class="font-semibold mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            How Staff Permissions Work
        </h3>
        <p class="text-gray-400 text-sm mb-4">
            Staff permissions are automatically synced from your Discord server roles. To add or remove staff:
        </p>
        <ol class="list-decimal list-inside text-sm text-gray-400 space-y-2">
            <li>Assign the appropriate Discord role in your Discord server</li>
            <li>The staff member logs in to this panel with Discord</li>
            <li>Their permissions are automatically set based on their Discord role</li>
        </ol>
        
        <div class="mt-4 p-4 bg-dark-800 rounded-lg">
            <p class="text-sm font-medium mb-2">Role Hierarchy:</p>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 text-xs rounded-full bg-purple-500/20 text-purple-400">Owner - Full Access</span>
                <span class="px-3 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Admin - All Actions + Delete</span>
                <span class="px-3 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">Moderator - Ban/Kick/Warn</span>
                <span class="px-3 py-1 text-xs rounded-full bg-gray-500/20 text-gray-400">Trial - Kick/Warn Only</span>
            </div>
        </div>
    </div>
</div>
@endsection
