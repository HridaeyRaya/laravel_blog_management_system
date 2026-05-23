@extends('layouts.app')

@section('content')
    <h1>Login</h1>

    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">
        @csrf

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>

        <button type="submit">Login</button>

        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </form>
@endsection
