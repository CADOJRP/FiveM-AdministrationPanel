@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Servers</h1>
            <p class="text-gray-400">Manage your FiveM servers</p>
        </div>
        
        <button onclick="openModal('addServerModal')" 
                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium">
            + Add Server
        </button>
    </div>
    
    <!-- Servers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($servers as $server)
        <div class="glass rounded-xl p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full {{ $server->isOnline() ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                    <h3 class="font-semibold text-lg">{{ $server->name }}</h3>
                </div>
                <span class="text-sm {{ $server->isOnline() ? 'text-green-400' : 'text-red-400' }}">
                    {{ $server->isOnline() ? 'Online' : 'Offline' }}
                </span>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Connection</span>
                    <span>{{ $server->connection ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Players</span>
                    <span>{{ $server->player_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Last Heartbeat</span>
                    <span>{{ $server->last_heartbeat_at?->diffForHumans() ?? 'Never' }}</span>
                </div>
            </div>
            
            <hr class="my-4 border-gray-700">
            
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Server Token</span>
                    <button onclick="showToken('{{ $server->id }}')" class="text-xs text-primary-400 hover:underline">
                        Show Token
                    </button>
                </div>
                <div id="token-{{ $server->id }}" class="hidden">
                    <code class="block p-2 bg-dark-800 rounded text-xs break-all">{{ $server->token }}</code>
                </div>
            </div>
            
            <div class="flex space-x-2 mt-4">
                <button onclick="regenerateToken({{ $server->id }})" 
                        class="flex-1 px-3 py-2 text-sm bg-yellow-600/20 text-yellow-400 hover:bg-yellow-600/30 rounded-lg">
                    Regenerate
                </button>
                <button onclick="deleteServer({{ $server->id }})" 
                        class="flex-1 px-3 py-2 text-sm bg-red-600/20 text-red-400 hover:bg-red-600/30 rounded-lg">
                    Delete
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-xl p-12 text-center">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
            </svg>
            <h3 class="text-xl font-semibold mb-2">No Servers</h3>
            <p class="text-gray-400 mb-4">Add your first FiveM server to get started.</p>
            <button onclick="openModal('addServerModal')" 
                    class="px-6 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium">
                Add Server
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Server Modal -->
<div id="addServerModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="glass rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Add Server</h3>
        <form id="addServerForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Server Name</label>
                <input type="text" name="name" required 
                       class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500"
                       placeholder="Main Server">
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Connection (Optional)</label>
                <input type="text" name="connection" 
                       class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg focus:outline-none focus:border-primary-500"
                       placeholder="play.yourserver.com:30120">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('addServerModal')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg">Add Server</button>
            </div>
        </form>
    </div>
</div>

<!-- Token Display Modal -->
<div id="tokenModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="glass rounded-xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-xl font-bold mb-4">Server Token</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-400 mb-2">Copy this token to your FiveM resource config.lua:</p>
            <code id="tokenDisplay" class="block p-4 bg-dark-800 rounded text-sm break-all font-mono"></code>
        </div>
        <div class="flex justify-end">
            <button onclick="closeModal('tokenModal')" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">Close</button>
        </div>
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

function showToken(serverId) {
    const tokenEl = document.getElementById('token-' + serverId);
    tokenEl.classList.toggle('hidden');
}

document.getElementById('addServerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("servers.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: formData.get('name'),
                connection: formData.get('connection')
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('tokenDisplay').textContent = data.token;
            closeModal('addServerModal');
            openModal('tokenModal');
            setTimeout(() => location.reload(), 3000);
        }
    } catch (error) {
        alert('Failed to add server');
    }
});

async function regenerateToken(serverId) {
    if (!confirm('Are you sure? You will need to update your FiveM resource with the new token.')) return;
    
    try {
        const response = await fetch(`/servers/${serverId}/regenerate-token`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('tokenDisplay').textContent = data.token;
            openModal('tokenModal');
        }
    } catch (error) {
        alert('Failed to regenerate token');
    }
}

async function deleteServer(serverId) {
    if (!confirm('Are you sure you want to delete this server?')) return;
    
    try {
        const response = await fetch(`/servers/${serverId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        alert('Failed to delete server');
    }
}
</script>
@endpush
@endsection
