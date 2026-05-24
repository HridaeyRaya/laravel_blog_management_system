<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->whereHas('status', fn($q) => $q->where('value','published'))->latest()->paginate(8);
        return view('posts.index', compact('posts'));
//          return response()->json($posts); // temporary
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
//          return response()->json(['categories' => $categories]); // temporary
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        // Auto-generate slug if empty
        $slug = !empty($validated['slug'])
            ? $validated['slug']
            : Str::slug($validated['title']);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'slug'  => $slug
        ]);

        $post->categories()->attach($validated['category_ids']);
        $post->status()->create(['value' => $validated['status']]);

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = Post::with(['user', 'comments.user', 'categories', 'status'])
            ->whereHas('status', fn($q) => $q->where('value','published'))
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('view_count');

        return view('posts.show', compact('post'));
//          return response()->json($post); // temporary
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $post = Post::with(['status', 'categories'])->where('slug', $slug)->firstOrFail();
//        $this->authorize('update', $post);
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
//          return response()->json(['post' => $post, 'categories' => $categories]); // temporary
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
//        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
//          return response()->json(['message' => 'Post deleted successfully!']); // temporary
    }
}

