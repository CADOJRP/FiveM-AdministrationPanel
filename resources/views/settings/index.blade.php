@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold">Settings</h1>
        <p class="text-gray-400">Configure panel settings</p>
    </div>
    
    <!-- Settings Form -->
    <div class="glass rounded-xl p-6">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-400">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- General Settings -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-700">General</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Community Name</label>
                        <input type="text" name="community_name" 
                               value="{{ $settings['community_name'] ?? 'JGN Gaming' }}"
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Discord Webhook URL</label>
                        <input type="url" name="discord_webhook" 
                               value="{{ $settings['discord_webhook'] ?? '' }}"
                               placeholder="https://discord.com/api/webhooks/..."
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                </div>
            </div>
            
            <!-- Trust Score Settings -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-700">Trust Score Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Base Score</label>
                        <input type="number" name="trust_score_base" 
                               value="{{ $settings['trust_score_base'] ?? 75 }}"
                               min="0" max="100"
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Warn Penalty</label>
                        <input type="number" name="trust_score_warn_penalty" 
                               value="{{ $settings['trust_score_warn_penalty'] ?? 3 }}"
                               min="0" max="50"
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Kick Penalty</label>
                        <input type="number" name="trust_score_kick_penalty" 
                               value="{{ $settings['trust_score_kick_penalty'] ?? 6 }}"
                               min="0" max="50"
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Ban Penalty</label>
                        <input type="number" name="trust_score_ban_penalty" 
                               value="{{ $settings['trust_score_ban_penalty'] ?? 10 }}"
                               min="0" max="50"
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Commend Bonus</label>
                        <input type="number" name="trust_score_commend_bonus" 
                               value="{{ $settings['trust_score_commend_bonus'] ?? 2 }}"
                               min="0" max="50"
                               class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Trust Score = Base + Playtime Bonus - (Warnings × Penalty) - (Kicks × Penalty) - (Bans × Penalty) + (Commends × Bonus)
                </p>
            </div>
            
            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
    
    <!-- Discord Role Configuration Info -->
    <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Discord Role IDs</h3>
        <p class="text-gray-400 text-sm mb-4">
            Configure these in your <code class="bg-dark-800 px-2 py-1 rounded">.env</code> file:
        </p>
        <div class="p-4 bg-dark-800 rounded-lg font-mono text-sm">
            <p class="text-gray-400"># Get role IDs: Right-click role in Discord → Copy ID</p>
            <p class="mt-2"><span class="text-blue-400">DISCORD_ROLE_OWNER</span>=<span class="text-green-400">your_owner_role_id</span></p>
            <p><span class="text-blue-400">DISCORD_ROLE_ADMIN</span>=<span class="text-green-400">your_admin_role_id</span></p>
            <p><span class="text-blue-400">DISCORD_ROLE_MODERATOR</span>=<span class="text-green-400">your_moderator_role_id</span></p>
            <p><span class="text-blue-400">DISCORD_ROLE_TRIAL</span>=<span class="text-green-400">your_trial_role_id</span></p>
        </div>
    </div>
</div>
@endsection
