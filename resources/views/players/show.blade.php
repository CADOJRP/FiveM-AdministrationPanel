@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('players.index') }}" class="mr-4 p-2 hover:bg-dark-800 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">{{ $player->name }}</h1>
                <p class="text-gray-400">Player Profile</p>
            </div>
        </div>
        
        @if($player->isBanned())
        <span class="px-4 py-2 bg-red-600 rounded-lg font-semibold">BANNED</span>
        @endif
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Player Info Card -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">Player Info</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-400">Trust Score</span>
                    <span class="px-3 py-1 rounded-full {{ $player->trust_score >= 75 ? 'bg-green-500/20 text-green-400' : ($player->trust_score >= 50 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                        {{ $player->trust_score }}%
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Playtime</span>
                    <span>{{ $player->playtime_formatted }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">First Joined</span>
                    <span>{{ $player->first_joined_at?->format('M d, Y') ?? 'Unknown' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Last Seen</span>
                    <span>{{ $player->last_seen_at?->diffForHumans() ?? 'Unknown' }}</span>
                </div>
            </div>
            
            <hr class="my-4 border-gray-700">
            
            <h3 class="font-semibold mb-3">Identifiers</h3>
            <div class="space-y-2">
                @foreach($player->identifiers as $identifier)
                <div class="p-2 bg-dark-800 rounded text-xs font-mono break-all">
                    <span class="text-primary-400">{{ $identifier->type }}:</span> 
                    @can('view_ips')
                    {{ str_replace($identifier->type . ':', '', $identifier->value) }}
                    @else
                    @if($identifier->type === 'ip')
                    <span class="text-gray-500">[Hidden]</span>
                    @else
                    {{ str_replace($identifier->type . ':', '', $identifier->value) }}
                    @endif
                    @endcan
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Actions Card -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">Actions</h2>
            
            <div class="space-y-3">
                @can('warn')
                <button onclick="openModal('warnModal')" 
                        class="w-full px-4 py-3 bg-yellow-600 hover:bg-yellow-700 rounded-lg font-medium transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Warn Player
                </button>
                @endcan
                
                @can('kick')
                <button onclick="openModal('kickModal')" 
                        class="w-full px-4 py-3 bg-orange-600 hover:bg-orange-700 rounded-lg font-medium transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Kick Player
                </button>
                @endcan
                
                @can('ban')
                <button onclick="openModal('banModal')" 
                        class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-medium transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    Ban Player
                </button>
                @endcan
                
                @can('commend')
                <button onclick="openModal('commendModal')" 
                        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-medium transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                    Commend Player
                </button>
                @endcan
            </div>
        </div>
        
        <!-- Stats Card -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">Statistics</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-dark-800 rounded-lg text-center">
                    <p class="text-2xl font-bold text-yellow-400">{{ $player->warnings->count() }}</p>
                    <p class="text-sm text-gray-400">Warnings</p>
                </div>
                <div class="p-4 bg-dark-800 rounded-lg text-center">
                    <p class="text-2xl font-bold text-orange-400">{{ $player->kicks->count() }}</p>
                    <p class="text-sm text-gray-400">Kicks</p>
                </div>
                <div class="p-4 bg-dark-800 rounded-lg text-center">
                    <p class="text-2xl font-bold text-red-400">{{ $player->bans->count() }}</p>
                    <p class="text-sm text-gray-400">Bans</p>
                </div>
                <div class="p-4 bg-dark-800 rounded-lg text-center">
                    <p class="text-2xl font-bold text-green-400">{{ $player->commendations->count() }}</p>
                    <p class="text-sm text-gray-400">Commends</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- History Tabs -->
    <div class="glass rounded-xl p-6">
        <div class="flex border-b border-gray-700 mb-4">
            <button class="px-4 py-2 border-b-2 border-primary-500 text-primary-400" onclick="showTab('bans')">Bans</button>
            <button class="px-4 py-2 text-gray-400 hover:text-white" onclick="showTab('warnings')">Warnings</button>
            <button class="px-4 py-2 text-gray-400 hover:text-white" onclick="showTab('kicks')">Kicks</button>
            <button class="px-4 py-2 text-gray-400 hover:text-white" onclick="showTab('commends')">Commends</button>
        </div>
        
        <!-- Bans Tab -->
        <div id="bans-tab">
            @forelse($player->bans as $ban)
            <div class="p-4 bg-dark-800 rounded-lg mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium">{{ $ban->reason }}</p>
                        <p class="text-sm text-gray-400">By {{ $ban->banned_by_name }} • {{ $ban->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded {{ $ban->active ? 'bg-red-500/20 text-red-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $ban->active ? ($ban->isPermanent() ? 'Permanent' : 'Expires ' . $ban->time_remaining) : 'Inactive' }}
                        </span>
                        @if($ban->active)
                        @can('unban')
                        <form action="{{ route('bans.destroy', $ban) }}" method="POST" class="inline mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">Unban</button>
                        </form>
                        @endcan
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">No bans on record</p>
            @endforelse
        </div>
        
        <!-- Other tabs hidden by default -->
        <div id="warnings-tab" class="hidden">
            @forelse($player->warnings as $warning)
            <div class="p-4 bg-dark-800 rounded-lg mb-3">
                <p class="font-medium">{{ $warning->reason }}</p>
                <p class="text-sm text-gray-400">By {{ $warning->warned_by_name }} • {{ $warning->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">No warnings on record</p>
            @endforelse
        </div>
        
        <div id="kicks-tab" class="hidden">
            @forelse($player->kicks as $kick)
            <div class="p-4 bg-dark-800 rounded-lg mb-3">
                <p class="font-medium">{{ $kick->reason }}</p>
                <p class="text-sm text-gray-400">By {{ $kick->kicked_by_name }} • {{ $kick->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">No kicks on record</p>
            @endforelse
        </div>
        
        <div id="commends-tab" class="hidden">
            @forelse($player->commendations as $commend)
            <div class="p-4 bg-dark-800 rounded-lg mb-3">
                <p class="font-medium">{{ $commend->reason }}</p>
                <p class="text-sm text-gray-400">By {{ $commend->commended_by_name }} • {{ $commend->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">No commendations on record</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Warn Modal -->
<div id="warnModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="glass rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Warn Player</h3>
        <form action="{{ route('players.warn', $player) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Reason</label>
                <textarea name="reason" required rows="3" 
                          class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('warnModal')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 rounded-lg">Warn</button>
            </div>
        </form>
    </div>
</div>

<!-- Kick Modal -->
<div id="kickModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="glass rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Kick Player</h3>
        <form action="{{ route('players.kick', $player) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Reason</label>
                <textarea name="reason" required rows="3" 
                          class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('kickModal')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 rounded-lg">Kick</button>
            </div>
        </form>
    </div>
</div>

<!-- Ban Modal -->
<div id="banModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="glass rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Ban Player</h3>
        <form action="{{ route('players.ban', $player) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Reason</label>
                <textarea name="reason" required rows="3" 
                          class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Duration (0 = Permanent)</label>
                <select name="duration" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500">
                    <option value="0">Permanent</option>
                    <option value="60">1 Hour</option>
                    <option value="1440">1 Day</option>
                    <option value="10080">1 Week</option>
                    <option value="43200">1 Month</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('banModal')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg">Ban</button>
            </div>
        </form>
    </div>
</div>

<!-- Commend Modal -->
<div id="commendModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="glass rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Commend Player</h3>
        <form action="{{ route('players.commend', $player) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Reason</label>
                <textarea name="reason" required rows="3" 
                          class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('commendModal')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg">Commend</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

function showTab(tab) {
    ['bans', 'warnings', 'kicks', 'commends'].forEach(t => {
        document.getElementById(t + '-tab').classList.add('hidden');
    });
    document.getElementById(tab + '-tab').classList.remove('hidden');
}

// Handle form submissions via AJAX
document.querySelectorAll('#warnModal form, #kickModal form, #banModal form, #commendModal form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            if (key !== '_token') data[key] = value;
        });
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Error: ' + (result.message || 'Action failed'));
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
            console.error(error);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});

// Handle unban forms via AJAX
document.querySelectorAll('form[action*="/bans/"]').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to remove this ban?')) return;
        
        try {
            const response = await fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to remove ban'));
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
            console.error(error);
        }
    });
});
</script>
@endpush
@endsection
