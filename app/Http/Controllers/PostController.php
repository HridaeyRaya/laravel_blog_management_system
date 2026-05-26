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
        $query = Post::with('user');

        // Admin sees all posts, others see only published
        if (!auth()->check() || !auth()->user()->roles->contains('name', 'admin')) {
            $query->whereHas('status', fn($q) => $q->where('value', 'published'));
        }

        // Search
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

        // Auto-generate slug from title if empty
        $slug = !empty($validated['slug'])
            ? $validated['slug']
            : Str::slug($validated['title']);

        // Make slug unique if already taken
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'slug'  => $slug,
        ]);

        $post->categories()->attach($validated['category_ids']);
        $post->status()->create(['value' => 'draft']);

        return redirect()->route('posts.index')->with('success', 'Post submitted for review!');
    }
    public function show(string $slug)
    {
        $query = Post::with(['user', 'comments.user', 'categories', 'status'])
            ->where('slug', $slug);

        // guests and regular users only see published
        if (!auth()->check() || !auth()->user()->roles->contains('name', 'admin')) {
            $query->whereHas('status', fn($q) => $q->where('value', 'published'));
        }

        $post = $query->firstOrFail();
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

    public function publish(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $this->authorize('publish', $post);

        $post->status()->updateOrCreate(
            ['statusable_id' => $post->id, 'statusable_type' => Post::class],
            ['value' => 'published']
        );

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post published successfully!');
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
