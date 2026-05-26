<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user')
            ->whereHas('status', fn($q) => $q->where('value', 'published'));

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('body', 'LIKE', '%' . $request->search . '%')
                    ->orWhereHas('user', fn($q) => $q->where('name', 'LIKE', '%' . $request->search . '%'));
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', fn($q) => $q->where('slug', $request->category));
        }

        $posts = $query->latest()->paginate(8)->withQueryString();

        // Get all categories for filter buttons
        $categories = Category::all();

        return view('posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $slug = !empty($validated['slug'])
            ? $validated['slug']
            : Str::slug($validated['title']);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'slug'  => $slug,
        ]);

        $post->categories()->attach($validated['category_ids']);
        $post->status()->create(['value' => $validated['status']]);

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post created successfully!');
    }

    public function show(string $slug)
    {
        $post = Post::with(['user', 'comments.user', 'categories', 'status'])
            ->whereHas('status', fn($q) => $q->where('value', 'published'))
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('view_count');

        return view('posts.show', compact('post'));
    }

    public function edit(string $slug)
    {
        $post = Post::with(['status', 'categories'])->where('slug', $slug)->firstOrFail();
        $this->authorize('update', $post);
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
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

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post updated successfully!');
    }

    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $this->authorize('delete', $post);

        $post->categories()->detach();
        $post->status()->delete();
        $post->comments()->delete();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}
