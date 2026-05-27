@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12">
        <div class="w-full max-w-md">

            {{-- Logo/Brand --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 shadow-lg mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
                <p class="text-gray-500 text-sm mt-1">Sign in to continue writing</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

                <div class="p-8">
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-5">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@example.com"
                                       autofocus
                                       class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('email') border-red-300 bg-red-50 @else border-gray-200 @enderror">
                            </div>
                            @error('email')
                            @if($message !== 'Your account has been suspended. Please contact support.')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @endif
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password"
                                       placeholder="••••••••"
                                       class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('password') border-red-300 bg-red-50 @else border-gray-200 @enderror">
                            </div>
                            @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="remember"
                                       class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-700 transition shadow-sm hover:shadow-emerald-200 hover:shadow-md">
                            Sign In →
                        </button>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-emerald-600 font-semibold hover:text-emerald-700">
                            Create one free
                        </a>
                    </p>
                </div>
            </div>

            {{-- Decorative text --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                By signing in, you agree to our terms of service
            </p>
        </div>
    </div>
@endsection
