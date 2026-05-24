@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <article class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Post Header -->
        <div class="px-8 pt-8 pb-4 border-b border-gray-200">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    By {{ $post->user->name }}
                </span>

                    <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $post->created_at->format('M d, Y') }}
                </span>

                    <span class="flexw items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ number_format($post->view_count) }} views
                </span>

                    @if($post->status && $post->status->value === 'draft')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Draft
                    </span>
                    @elseif($post->status && $post->status->value === 'published')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Published
                    </span>
                    @endif
                </div>

                <!-- Edit/Delete Buttons -->
                @auth
                    @if(auth()->id() === $post->user_id)
                        <div class="flex gap-2">
                            <a href="{{ route('posts.edit', $post->slug) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition"
                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Categories -->
        @if($post->categories->count())
            <div class="px-8 pt-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->categories as $category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $category->name }}
                    </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Post Body - FIXED ALIGNMENT -->
        <div class="px-8 pb-8 pt-4">
            <div class="text-gray-700 leading-relaxed">
                {{ $post->body }}
            </div>
        </div>
    </article>

    <!-- Comments Section -->
    <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Comments ({{ $post->comments->count() }})</h3>

        @auth
            <form method="POST" action="#" class="mb-8">
                @csrf
                <div class="mb-4">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                    <textarea name="body" id="body" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Write your comment here..."></textarea>
                    @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Post Comment
                </button>
            </form>
        @else
            <div class="bg-gray-50 rounded-lg p-4 mb-8 text-center">
                <p class="text-gray-600">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to leave a comment.
                </p>
            </div>
        @endauth

        <!-- Comments List -->
        <div class="space-y-6">
            @forelse($post->comments as $comment)
                <div class="border-b border-gray-200 pb-4 last:border-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr($comment->user->name, 0, 1) }}
                            </span>
                            </div>
                            <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y') }}</span>
                    </div>
                    <p class="text-gray-700 ml-10">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </div>
@endsection
