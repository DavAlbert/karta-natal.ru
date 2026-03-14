@extends('admin.layout')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.blog.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to posts</a>
</div>

<h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $post ? 'Edit Post' : 'New Post' }}</h1>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 text-sm">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6 text-sm">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ $post ? route('admin.blog.update', $post) : route('admin.blog.store') }}" enctype="multipart/form-data">
    @csrf
    @if($post) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post?->title) }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-gray-400">(auto-generated if empty)</span></label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $post?->slug) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt <span class="text-gray-400">(shown on blog listing)</span></label>
                    <textarea name="excerpt" id="excerpt" rows="2"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('excerpt', $post?->excerpt) }}</textarea>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content (BBCode)</label>
                    <div class="flex flex-wrap gap-1 mb-2">
                        <button type="button" onclick="insertBB('b')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200 font-bold">B</button>
                        <button type="button" onclick="insertBB('i')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200 italic">I</button>
                        <button type="button" onclick="insertBB('u')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200 underline">U</button>
                        <button type="button" onclick="insertBB('h2')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">H2</button>
                        <button type="button" onclick="insertBB('h3')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">H3</button>
                        <button type="button" onclick="insertBB('h4')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">H4</button>
                        <button type="button" onclick="insertBB('quote')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">Quote</button>
                        <button type="button" onclick="insertBBUrl()" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">Link</button>
                        <button type="button" onclick="insertBB('img')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">Img</button>
                        <button type="button" onclick="insertBBList()" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">List</button>
                        <button type="button" onclick="insertTag('[hr]')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">HR</button>
                        <button type="button" onclick="insertBB('cta')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200 text-purple-700 font-semibold">CTA</button>
                        <button type="button" onclick="insertBB('center')" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">Center</button>
                    </div>
                    <textarea name="content" id="content" rows="20" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">{{ old('content', $post?->content) }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                    <select name="locale" id="locale" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(config('app.available_locales', ['en']) as $loc)
                        <option value="{{ $loc }}" {{ old('locale', $post?->locale ?? 'en') === $loc ? 'selected' : '' }}>{{ strtoupper($loc) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                        {{ old('is_published', $post?->is_published) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_published" class="ml-2 text-sm text-gray-700">Published</label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                    {{ $post ? 'Update Post' : 'Create Post' }}
                </button>
            </div>

            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div>
                    <label for="banner" class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                    @if($post?->banner)
                    <div class="mb-2">
                        <img src="{{ Storage::url($post->banner) }}" alt="" class="w-full rounded-lg">
                        <label class="flex items-center mt-2">
                            <input type="checkbox" name="remove_banner" value="1" class="rounded border-gray-300 text-red-600">
                            <span class="ml-2 text-sm text-red-600">Remove banner</span>
                        </label>
                    </div>
                    @endif
                    <input type="file" name="banner" id="banner" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700">SEO</h3>
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post?->meta_title) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('meta_description', $post?->meta_description) }}</textarea>
                </div>
                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords <span class="text-gray-400">(comma-separated)</span></label>
                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $post?->meta_keywords) }}"
                        placeholder="natal chart, astrology, horoscope, zodiac"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">BBCode Reference</h3>
                <div class="text-xs text-gray-500 space-y-1 font-mono">
                    <div>[b]bold[/b]</div>
                    <div>[i]italic[/i]</div>
                    <div>[u]underline[/u]</div>
                    <div>[h2]heading[/h2]</div>
                    <div>[h3]subheading[/h3]</div>
                    <div>[url=https://...]text[/url]</div>
                    <div>[img]url[/img]</div>
                    <div>[quote]text[/quote]</div>
                    <div>[list][*]item[*]item[/list]</div>
                    <div>[hr]</div>
                    <div>[cta]Your text[/cta]</div>
                    <div>[center]text[/center]</div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function insertBB(tag) {
    const ta = document.getElementById('content');
    const start = ta.selectionStart;
    const end = ta.selectionEnd;
    const selected = ta.value.substring(start, end);
    const replacement = `[${tag}]${selected}[/${tag}]`;
    ta.value = ta.value.substring(0, start) + replacement + ta.value.substring(end);
    ta.focus();
    ta.selectionStart = start + tag.length + 2;
    ta.selectionEnd = start + tag.length + 2 + selected.length;
}

function insertBBUrl() {
    const url = prompt('Enter URL:');
    if (!url) return;
    const ta = document.getElementById('content');
    const start = ta.selectionStart;
    const end = ta.selectionEnd;
    const selected = ta.value.substring(start, end) || 'link text';
    const replacement = `[url=${url}]${selected}[/url]`;
    ta.value = ta.value.substring(0, start) + replacement + ta.value.substring(end);
    ta.focus();
}

function insertBBList() {
    const ta = document.getElementById('content');
    const start = ta.selectionStart;
    const replacement = `[list]\n[*]Item 1\n[*]Item 2\n[*]Item 3\n[/list]`;
    ta.value = ta.value.substring(0, start) + replacement + ta.value.substring(ta.selectionEnd);
    ta.focus();
}

function insertTag(tag) {
    const ta = document.getElementById('content');
    const start = ta.selectionStart;
    ta.value = ta.value.substring(0, start) + tag + ta.value.substring(ta.selectionEnd);
    ta.focus();
}
</script>
@endsection
