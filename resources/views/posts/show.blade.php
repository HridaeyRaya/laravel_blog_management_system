@extends('layouts.app')

@section('content')
    <article>
        <h1>{{ $post->title }}</h1>
        <small>
            By {{ $post->user->name }} |
            {{ $post->created_at->format('M d, Y') }} |
            {{ $post->view_count }} views |
            Status: {{ $post->status->value }}
        </small>

        <div>{{ $post->body }}</div>

        {{-- Categories --}}
        @if($post->categories->count())
            <div>
                Categories:
                @foreach($post->categories as $category)
                    <span>{{ $category->name }}</span>
                @endforeach
            </div>
        @endif

        {{-- Auth actions --}}
{{--        @auth--}}
{{--            @can('update', $post)--}}
{{--                <a href="{{ route('posts.edit', $post->slug) }}">Edit</a>--}}
{{--            @endcan--}}

{{--            @can('delete', $post)--}}
{{--                <form action="{{ route('posts.destroy', $post->slug) }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    @method('DELETE')--}}
{{--                    <button type="submit">Delete</button>--}}
{{--                </form>--}}
{{--            @endcan--}}
{{--        @endauth--}}

{{--        Temporary --}}
        @auth
            <a href="{{ route('posts.edit', $post->slug) }}">Edit</a>

            <form action="{{ route('posts.destroy', $post->slug) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        @endauth

        {{-- Comments --}}
        <h3>Comments ({{ $post->comments->count() }})</h3>

        @forelse($post->comments as $comment)
            <div>
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->body }}</p>
                <small>{{ $comment->created_at->format('M d, Y') }}</small>
            </div>
        @empty
            <p>No comments yet.</p>
        @endforelse
    </article>
@endsection
