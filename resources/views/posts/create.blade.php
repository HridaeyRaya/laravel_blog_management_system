@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>

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

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div>
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}">
            @error('slug') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Body</label>
            <textarea name="body" rows="10">{{ old('body') }}</textarea>
            @error('body') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Status</label>
            <select name="status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            @error('status') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Categories</label>
            @foreach($categories as $category)
                <label>
                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}">
                    {{ $category->name }}
                </label>
            @endforeach
            @error('category_ids') <span>{{ $message }}</span> @enderror
        </div>

        <button type="submit">Create Post</button>
    </form>
@endsection
