<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')->latest()->paginate(25);

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'banner' => 'nullable|image|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:500',
            'locale' => 'required|string|in:' . implode(',', config('app.available_locales', ['en'])),
            'is_published' => 'boolean',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['slug'] = $validated['slug'] ?: BlogPost::generateSlug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('blog/banners', 'public');
        }

        $post = BlogPost::create($validated);

        return redirect()->route('admin.blog.edit', $post)->with('success', 'Post created.');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.blog.form', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'banner' => 'nullable|image|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:500',
            'locale' => 'required|string|in:' . implode(',', config('app.available_locales', ['en'])),
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        if ($request->boolean('is_published') && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif (!$request->boolean('is_published')) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('banner')) {
            if ($post->banner) {
                Storage::disk('public')->delete($post->banner);
            }
            $validated['banner'] = $request->file('banner')->store('blog/banners', 'public');
        }

        if ($request->boolean('remove_banner') && !$request->hasFile('banner')) {
            if ($post->banner) {
                Storage::disk('public')->delete($post->banner);
            }
            $validated['banner'] = null;
        }

        $post->update($validated);

        return redirect()->route('admin.blog.edit', $post)->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $post)
    {
        if ($post->banner) {
            Storage::disk('public')->delete($post->banner);
        }

        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Post deleted.');
    }

    public function preview(BlogPost $post)
    {
        return view('blog.show', ['post' => $post]);
    }
}
