<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Staff Administration Panel for {{ config('app.community_name') }}">
    <meta name="theme-color" content="#0f172a">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        dark: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .glass {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-dark-950 text-white min-h-screen">
    @auth
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 glass fixed h-full">
            <div class="p-6">
                <h1 class="text-xl font-bold text-primary-500">{{ config('app.community_name', 'Admin Panel') }}</h1>
            </div>
            
            <nav class="px-4 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-dark-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('players.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('players.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-dark-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Players
                </a>
                
                @can('edit_servers')
                <a href="{{ route('servers.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('servers.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-dark-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    </svg>
                    Servers
                </a>
                @endcan
                
                @can('edit_staff')
                <a href="{{ route('staff.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('staff.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-dark-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Staff
                </a>
                @endcan
                
                @can('edit_panel')
                <a href="{{ route('settings.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-dark-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
                @endcan
            </nav>
            
            <!-- User info at bottom -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full">
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ auth()->user()->discord_username }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 text-sm text-red-400 hover:bg-red-900/20 rounded-lg">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main content -->
        <main class="ml-64 flex-1 p-8">
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-lg text-green-400">
                {{ session('success') }}
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-400">
                {{ session('error') }}
            </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    @else
    @yield('content')
    @endauth
    
    @stack('scripts')
</body>
</html>
