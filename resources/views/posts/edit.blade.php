@extends('layouts.app')

@section('title', 'Edit Post: ' . $post->title)

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Post: {{ $post->title }}</h1>

        <form method="POST" action="{{ route('posts.update', $post->slug) }}">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $post->title) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-6">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                <input type="text" name="slug" id="slug"
                       value="{{ old('slug', $post->slug) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror"
                       required>
                <p class="mt-1 text-sm text-gray-500">URL-friendly version of the title (e.g., my-first-post)</p>
                @error('slug')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Body -->
            <div class="mb-6">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">Body *</label>
                <textarea name="body" id="body" rows="10"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('body') border-red-500 @enderror"
                          required>{{ old('body', $post->body) }}</textarea>
                @error('body')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status - Fixed to match controller -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', $post->status->value ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status->value ?? 'draft') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categories - Fixed to match controller's 'category_ids' -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @foreach($categories as $category)
                        @php
                            $postCategoryIds = $post->categories->pluck('id')->toArray();
                        @endphp
                        <label class="flex items-center">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                   {{ in_array($category->id, old('category_ids', $postCategoryIds)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category_ids')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('posts.show', $post->slug) }}"
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Post
                </button>
            </div>
        </form>
    </div>
@endsection
