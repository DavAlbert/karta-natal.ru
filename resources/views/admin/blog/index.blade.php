@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Blog Posts ({{ $posts->total() }})</h1>
    <a href="{{ route('admin.blog.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">New Post</a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Locale</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Published</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($posts as $post)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">
                    <a href="{{ route('admin.blog.edit', $post) }}" class="font-medium hover:text-blue-600">{{ $post->title }}</a>
                    <div class="text-xs text-gray-400">{{ $post->locale === 'en' ? '' : '/' . $post->locale }}/blog/{{ $post->slug }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ strtoupper($post->locale) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $post->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $post->author?->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                    <a href="{{ route('admin.blog.edit', $post) }}" class="text-blue-600 hover:underline">Edit</a>
                    @if($post->is_published)
                        <a href="{{ route(($post->locale === 'en' ? '' : $post->locale . '.') . 'blog.show', $post->slug) }}" class="text-green-600 hover:underline" target="_blank">View</a>
                    @else
                        <a href="{{ route('admin.blog.preview', $post) }}" class="text-gray-600 hover:underline" target="_blank">Preview</a>
                    @endif
                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No posts yet. <a href="{{ route('admin.blog.create') }}" class="text-blue-600 hover:underline">Create your first post</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($posts->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
