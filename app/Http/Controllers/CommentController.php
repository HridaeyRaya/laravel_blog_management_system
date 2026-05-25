<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $post = Post::where('slug', $slug)->firstOrFail();

        $post->comments()->create([
            'body'    => $request->body,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('posts.show', $slug)->with('success', 'Comment added!');
    }

    public function destroy(string $slug, Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('posts.show', $slug)->with('success', 'Comment deleted!');
    }
}
