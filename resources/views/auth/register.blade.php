@extends('layouts.app')

@section('content')
    <h1>Register</h1>

    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span>{{ $message }}</span> @enderror
        </div>

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
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation">
            @error('password_confirmation') <span>{{ $message }}</span> @enderror
        </div>

        <button type="submit">Register</button>

        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </form>
@endsection
