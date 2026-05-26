@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')

    {{-- Hero Section --}}
    <div class="relative mb-12 p-10 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        <div class="relative">
            <span class="inline-block px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-full mb-4">
                📝 Latest Articles
            </span>
            <h1 class="text-4xl font-bold text-white mb-2">Blog Posts</h1>
            <p class="text-emerald-100 text-lg">Explore our latest articles and insights</p>
        </div>
    </div>

    @if($posts->count() > 0)
        {{-- Posts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10 flex justify-center">
            {{ $posts->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-dashed border-emerald-200 p-16 text-center">
            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No posts yet</h3>
            <p class="text-gray-400 text-sm mb-6">Be the first to share something amazing!</p>
            @auth
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition">
                    Write First Post
                </a>
            @endauth
        </div>
    @endif

@endsection
