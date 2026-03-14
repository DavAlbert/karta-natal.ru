<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'banner',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    public function scopeLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function renderContent(): string
    {
        $text = e($this->content);

        // BBCode to HTML
        $bbcode = [
            '/\[b\](.*?)\[\/b\]/s' => '<strong>$1</strong>',
            '/\[i\](.*?)\[\/i\]/s' => '<em>$1</em>',
            '/\[u\](.*?)\[\/u\]/s' => '<u>$1</u>',
            '/\[s\](.*?)\[\/s\]/s' => '<del>$1</del>',
            '/\[h2\](.*?)\[\/h2\]/s' => '<h2 class="text-2xl font-bold text-white mt-8 mb-4">$1</h2>',
            '/\[h3\](.*?)\[\/h3\]/s' => '<h3 class="text-xl font-semibold text-white mt-6 mb-3">$1</h3>',
            '/\[h4\](.*?)\[\/h4\]/s' => '<h4 class="text-lg font-semibold text-indigo-300 mt-4 mb-2">$1</h4>',
            '/\[url=(.*?)\](.*?)\[\/url\]/s' => '<a href="$1" class="text-indigo-400 hover:text-indigo-300 underline">$2</a>',
            '/\[url\](.*?)\[\/url\]/s' => '<a href="$1" class="text-indigo-400 hover:text-indigo-300 underline">$1</a>',
            '/\[img\](.*?)\[\/img\]/s' => '<img src="$1" alt="" class="rounded-lg my-4 max-w-full">',
            '/\[quote\](.*?)\[\/quote\]/s' => '<blockquote class="border-l-4 border-indigo-500 pl-4 my-4 text-gray-300 italic">$1</blockquote>',
            '/\[code\](.*?)\[\/code\]/s' => '<pre class="bg-gray-900 rounded-lg p-4 my-4 overflow-x-auto text-sm text-gray-300"><code>$1</code></pre>',
            '/\[list\](.*?)\[\/list\]/s' => '<ul class="list-disc list-inside space-y-1 my-4 text-gray-300">$1</ul>',
            '/\[\*\](.*?)(?=\[\*\]|\[\/list\])/s' => '<li>$1</li>',
            '/\[color=(.*?)\](.*?)\[\/color\]/s' => '<span style="color:$1">$2</span>',
            '/\[size=(.*?)\](.*?)\[\/size\]/s' => '<span style="font-size:$1">$2</span>',
            '/\[center\](.*?)\[\/center\]/s' => '<div class="text-center">$1</div>',
            '/\[hr\]/s' => '<hr class="border-gray-700 my-6">',
            '/\[cta\](.*?)\[\/cta\]/s' => '<div class="my-8 p-6 rounded-xl bg-gradient-to-r from-indigo-900/50 to-purple-900/50 border border-indigo-500/30 text-center"><p class="text-lg text-gray-200 mb-4">$1</p><a href="/#calcForm" class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg shadow-indigo-500/25">Calculate Your Natal Chart Free</a></div>',
        ];

        foreach ($bbcode as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        // Convert line breaks
        $text = nl2br($text);

        return $text;
    }
}
