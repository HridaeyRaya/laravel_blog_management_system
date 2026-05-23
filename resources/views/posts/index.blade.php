@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Blog Posts</h1>
        <p class="text-gray-600 mt-2">Explore our latest articles and insights</p>
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No posts found</h3>
            <p class="mt-2 text-gray-500">Check back later for new content!</p>
        </div>
    @endif
@endsection
