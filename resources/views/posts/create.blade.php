@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center space-x-2 text-emerald-600 hover:text-emerald-700 text-sm font-medium transition mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Back to Posts</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
            <p class="text-gray-500 mt-1">Share your thoughts with the world</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-1.5 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

            <div class="p-8">
                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-sm font-medium text-red-700 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                               value="{{ old('title') }}"
                               placeholder="Enter your post title..."
                               class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('title') border-red-300 bg-red-50 @else border-gray-200 @enderror">
                        @error('title')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-sm text-gray-400">/posts/</span>
                            <input type="text" name="slug" id="slug"
                                   value="{{ old('slug') }}"
                                   placeholder="auto-generated-from-title"
                                   class="w-full pl-16 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('slug') border-red-300 bg-red-50 @else border-gray-200 @enderror">
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Leave empty to auto-generate from title</p>
                        @error('slug')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Body --}}
                    <div class="mb-6">
                        <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">
                            Body <span class="text-red-500">*</span>
                        </label>
                        <textarea name="body" id="body" rows="12"
                                  placeholder="Write your post content here... (minimum 100 characters)"
                                  class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none @error('body') border-red-300 bg-red-50 @else border-gray-200 @enderror">{{ old('body') }}</textarea>
                        @error('body')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status + Categories Row --}}
                    <div class="grid grid-cols-1 gap-6 mb-8">
                        {{-- Categories --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Categories</label>
                            <div class="border border-gray-200 rounded-xl p-3 max-h-36 overflow-y-auto space-y-2">
                                @foreach($categories as $category)
                                    <label class="flex items-center space-x-2 cursor-pointer group">
                                        <input type="checkbox"
                                               name="category_ids[]"
                                               value="{{ $category->id }}"
                                               {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-600 group-hover:text-emerald-600 transition">
                        {{ $category->name }}
                    </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('category_ids')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Draft notice --}}
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-sm text-amber-700">
                            📝 Your post will be saved as <strong>Draft</strong> and reviewed by an admin before publishing.
                        </p>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('posts.index') }}"
                           class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition">
                            Submit for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('title').addEventListener('input', function() {
            let slug = this.value.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endsection
