@extends('layouts.app')

@section('title', $post->title)

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center space-x-2 text-emerald-600 hover:text-emerald-700 text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Back to Posts</span>
        </a>
    </div>

    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Top accent --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

        {{-- Post Header --}}
        <div class="px-8 pt-8 pb-6 border-b border-gray-100">
            {{-- Status + Date --}}
            <div class="flex items-center justify-between mb-4">
                @if($post->status && $post->status->value === 'draft')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        ● Draft
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ● Published
                    </span>
                @endif
                <span class="text-sm text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
            </div>

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $post->title }}
            </h1>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center">
                            <span class="text-sm font-bold text-emerald-700">
                                {{ substr($post->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $post->user->name }}</p>
                            <p class="text-xs text-gray-400">Author</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-1 text-sm text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ number_format($post->view_count) }} views</span>
                    </div>
                </div>

                {{-- Edit/Delete/Publish --}}
                <div class="flex gap-2">
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post->slug) }}"
                           class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition">
                            Edit Post
                        </a>
                    @endcan

                    @can('publish', $post)
                        @if($post->status?->value !== 'published')
                            <form method="POST" action="{{ route('posts.publish', $post->slug) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 text-sm font-medium rounded-xl hover:bg-blue-600 hover:text-white transition">
                                    Publish
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('delete', $post)
                        <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this post?')"
                                    class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-xl hover:bg-red-600 hover:text-white transition">
                                Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Categories --}}
        @if($post->categories->count())
            <div class="px-8 pt-5">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->categories as $category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Post Body --}}
        <div class="px-8 py-8">
            <div class="text-gray-700 leading-relaxed text-base whitespace-pre-line">
                {{ trim($post->body) }}
            </div>
        </div>
    </article>

    {{-- Comments Section --}}
    <div class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center space-x-2">
                <span>Comments</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $post->comments->count() }}
                </span>
            </h3>

            @auth
                <form method="POST" action="{{ route('comments.store', $post->slug)}}" class="mb-8">
                    @csrf
                    <div class="mb-3">
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                            Add a comment
                        </label>
                        <textarea name="body" id="body" rows="3"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm resize-none"
                                  placeholder="Share your thoughts...">{{ old('body') }}</textarea>
                        @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="px-5 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition">
                        Post Comment
                    </button>
                </form>
            @else
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 mb-8 text-center">
                    <p class="text-sm text-gray-600">
                        <a href="{{ route('login') }}" class="text-emerald-600 font-medium hover:underline">Login</a>
                        to leave a comment.
                    </p>
                </div>
            @endauth

            {{-- Comments List --}}
            <div class="space-y-5">
                @forelse($post->comments as $comment)
                    <div class="flex space-x-3 pb-5 border-b border-gray-50 last:border-0">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-emerald-700">
                                {{ substr($comment->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</span>
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->format('M d, Y') }}</span>
                                    @auth
                                        @if(auth()->id() === $comment->user_id)
                                            <form action="{{ route('comments.destroy', [$post->slug, $comment->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Delete this comment?')"
                                                        class="text-xs text-red-400 hover:text-red-600 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">No comments yet. Be the first!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
