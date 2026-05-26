<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'status', 'categories', 'comments']);

        if (!$request->user()) {
            $query->whereHas('status', fn($q) => $q->where('value', 'published'));
        } elseif (!$request->user()->roles->contains('name', 'admin')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('status', fn($q) => $q->where('value', 'published'))
                    ->orWhere('user_id', $request->user()->id);
            });
        }

        $posts = $query->latest()->paginate(15);

        return new PostCollection($posts);
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'slug'  => $validated['slug'],
        ]);

        $post->categories()->attach($validated['category_ids']);
        $post->status()->create(['value' => $validated['status']]);

        return (new PostResource($post->load(['user', 'status', 'categories', 'comments'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $slug)
    {
        $post = Post::with(['user', 'status', 'categories', 'comments'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $validated = $request->validated();

        $post->update([
            'title' => $validated['title'] ?? $post->title,
            'body'  => $validated['body'] ?? $post->body,
            'slug'  => $validated['slug'] ?? $post->slug,
        ]);

        if (isset($validated['category_ids'])) {
            $post->categories()->sync($validated['category_ids']);
        }

        if (isset($validated['status'])) {
            $post->status()->updateOrCreate(
                ['statusable_id' => $post->id, 'statusable_type' => Post::class],
                ['value' => $validated['status']]
            );
        }

        return (new PostResource($post->load(['user', 'status', 'categories', 'comments'])))
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $this->authorize('delete', $post);

        $post->categories()->detach();
        $post->status()->delete();
        $post->comments()->delete();
        $post->delete();

        return response()->json(null, 204);
    }
}
