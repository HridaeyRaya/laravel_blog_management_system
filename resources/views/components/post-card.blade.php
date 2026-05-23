@props(['post'])

<article>
    <h2>
        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
    </h2>
    <p>{{ Str::limit($post->body, 150) }}</p>
    <small>
        By {{ $post->user->name }} |
        {{ $post->created_at->format('M d, Y') }} |
        {{ $post->view_count }} views
    </small>
</article>
