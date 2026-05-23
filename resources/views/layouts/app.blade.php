<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>@yield('title', 'Blog Management App')</title>
    <style>
        .flash-message {
            transition: opacity 0.5s ease;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        #dropdown-menu {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

{{-- Navigation --}}
<nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <a href="{{ route('posts.index') }}" class="text-2xl font-bold text-gray-800 hover:text-blue-600 transition">
                    BlogManagement
                </a>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        Write Post
                    </a>

                    <div class="relative">
                        <button onclick="toggleDropdown()"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                            <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform" id="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="dropdown-menu"
                             class="absolute right-0 w-48 bg-white rounded-lg shadow-lg py-2 mt-2 hidden border border-gray-100">
                            <div class="px-4 py-2 text-sm text-gray-500 border-b border-gray-100">
                                Signed in as<br>
                                <span class="font-medium text-gray-900">{{ auth()->user()->email }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">Register</a>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    @if(session('success'))
        <div class="flash-message bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flash-message bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
</div>

{{-- Main Content --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <p class="text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} BlogManagement App. All rights reserved.
        </p>
    </div>
</footer>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        const icon = document.getElementById('dropdown-icon');
        menu.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    // Close dropdown when clicking outside
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

    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        let flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(function(message) {
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 500);
        });
    }, 5000);
</script>
</body>
</html>
