@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', $post->slug) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}">
            @error('title') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $post->slug) }}">
            @error('slug') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Body</label>
            <textarea name="body" rows="10">{{ old('body', $post->body) }}</textarea>
            @error('body') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Status</label>
            <select name="status">
                <option value="draft" {{ $post->status?->value === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ $post->status?->value === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Categories</label>
            @foreach($categories as $category)
                <label>
                    <input type="checkbox"
                           name="category_ids[]"
                           value="{{ $category->id }}"
                        {{ $post->categories->contains($category->id) ? 'checked' : '' }}>
                    {{ $category->name }}
                </label>
            @endforeach
            @error('category_ids') <span>{{ $message }}</span> @enderror
        </div>

        <button type="submit">Update Post</button>
    </form>
@endsection
