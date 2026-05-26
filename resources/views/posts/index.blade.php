

@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')

    {{-- Hero Section --}}
    <div class="relative mb-8 p-8 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 overflow-hidden">
        <div class="absolute inset-0 opacity-5">
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
        <span class="inline-block px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-full mb-3">
            📝 Latest Articles
        </span>
            <h1 class="text-4xl font-bold text-white mb-1">Blog Posts</h1>
            <p class="text-emerald-100 text-lg">Explore our latest articles and insights</p>
        </div>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('posts.index') }}" method="GET" class="mb-5">
        <div class="flex gap-3">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search posts by title, content or author..."
                class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 outline-none"
            >
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <button type="submit"
                    class="px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium">
                Search
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('posts.index') }}"
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-medium">
                    Clear
                </a>
            @endif
        </div>
        @if(request('search'))
            <p class="mt-3 text-sm text-gray-500">
                Showing results for <span class="font-medium text-emerald-600">"{{ request('search') }}"</span>
                — {{ $posts->total() }} post(s) found
            </p>
        @endif
    </form>

    {{-- Category Filter Buttons --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('posts.index', array_filter(['search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition
       {{ !request('category') ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-400 hover:text-emerald-600' }}">
            All
        </a>
        @foreach($categories as $category)
            <a href="{{ route('posts.index', array_filter(['category' => $category->slug, 'search' => request('search')])) }}"
               class="px-4 py-2 rounded-xl text-sm font-medium transition
           {{ request('category') === $category->slug ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-400 hover:text-emerald-600' }}">
                {{ $category->name }}
            </a>
        @endforeach
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
            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                @if(request('search') || request('category'))
                    No posts found
                @else
                    No posts yet
                @endif
            </h3>
            <p class="text-gray-400 text-sm mb-6">
                @if(request('search') || request('category'))
                    Try a different search term or category
                @else
                    Be the first to share something amazing!
                @endif
            </p>
            @auth
                @if(!request('search') && !request('category'))
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition">
                        Write First Post
                    </a>
                @endif
            @endauth
        </div>
    @endif

@endsection
