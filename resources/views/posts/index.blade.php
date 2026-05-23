@extends('layouts.app')

@section('content')
    <h1>All Posts</h1>

    @foreach($posts as $post)
        <x-post-card :post="$post" />
    @endforeach

    {{ $posts->links() }}
@endsection
