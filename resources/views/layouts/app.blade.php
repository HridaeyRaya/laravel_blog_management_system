<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>@yield('title', 'Blog Management App')</title>
    <style>
        .flash-message { transition: opacity 0.5s ease; }
        .rotate-180 { transform: rotate(180deg); }
        #dropdown-menu { transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

{{-- Navigation --}}
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('posts.index') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">BlogManagement</span>
                </a>
            </div>

            {{-- Nav Actions --}}
            <div class="flex items-center space-x-3">
                @auth
                    {{-- Admin Link --}}
                    @if(auth()->user()->roles()->where('name', \App\Enum\RoleName::Admin->value)->exists())
                        <a href="{{ route('admin.permissions') }}"
                           class="hidden sm:inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition">
                            ⚙ Permissions
                        </a>
                    @endif

                    {{-- Write Post --}}
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                        + Write Post
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative">
                        <button onclick="toggleDropdown()"
                                class="flex items-center space-x-2 pl-3 pr-2 py-2 rounded-xl hover:bg-gray-50 transition border border-gray-100">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center">
                                <span class="text-xs font-bold text-emerald-700">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-700 max-w-24 truncate">
                                {{ auth()->user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" id="dropdown-icon"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="dropdown-menu"
                             class="absolute right-0 w-52 bg-white rounded-2xl shadow-lg py-2 mt-2 hidden border border-gray-100">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-xs text-gray-400">Signed in as</p>
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->roles()->where('name', \App\Enum\RoleName::Admin->value)->exists())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mt-1">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 mt-1">
                                        User
                                    </span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                        Get Started
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    @if(session('success'))
        <div class="flash-message flex items-center space-x-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flash-message flex items-center space-x-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif
</div>

{{-- Main Content --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="border-t border-gray-100 mt-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded-md bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">BlogManagement</span>
            </div>
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        const icon = document.getElementById('dropdown-icon');
        menu.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('dropdown-menu');
        const button = event.target.closest('button');
        if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                const icon = document.getElementById('dropdown-icon');
                if (icon) icon.classList.remove('rotate-180');
            }
        }
    });

    setTimeout(function() {
        let flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(function(message) {
            message.style.opacity = '0';
            setTimeout(function() { message.remove(); }, 500);
        });
    }, 5000);
</script>
</body>
</html>
