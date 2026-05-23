<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management App</title>
</head>
<body>

{{-- Navigation --}}
<nav>
    <a href="{{ route('posts.index') }}">Home</a>

    @auth
        <a href="{{ route('posts.create') }}">Write Post</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
        <span>Hello, {{ auth()->user()->name }}</span>
    @endauth

    @guest
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endguest
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer>
    <p>&copy; {{ date('Y') }} Blog Management App</p>
</footer>

</body>
</html>
