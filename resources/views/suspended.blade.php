@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg p-12 text-center max-w-md">
            <h1 class="text-3xl font-bold text-red-600 mb-4">Account Suspended</h1>
            <p class="text-gray-600 mb-6">Your account has been suspended. Please contact support.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection
