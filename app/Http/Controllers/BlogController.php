<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $posts = BlogPost::published()
            ->locale($locale)
            ->latest('published_at')
            ->paginate(12);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $locale = app()->getLocale();

        $post = BlogPost::published()
            ->where('slug', $slug)
            ->where('locale', $locale)
            ->firstOrFail();

        $related = BlogPost::published()
            ->locale($post->locale)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
