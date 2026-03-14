@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Chat Messages ({{ $messages->total() }})</h1>
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <form method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search message content..."
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            <select name="role" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">All roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="assistant" {{ request('role') === 'assistant' ? 'selected' : '' }}>Assistant</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">Filter</button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.messages') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
            @endif
        </form>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($messages as $message)
        <div class="px-6 py-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $message->role === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}
                    ">{{ $message->role }}</span>
                    <span class="text-sm text-gray-500">
                        @if($message->natalChart && $message->natalChart->user)
                            <a href="{{ route('admin.users.show', $message->natalChart->user) }}" class="text-blue-600 hover:underline">{{ $message->natalChart->user->email }}</a>
                        @else
                            Guest
                        @endif
                    </span>
                    <span class="text-sm text-gray-400">
                        Chart: {{ $message->natalChart->name ?? '—' }}
                    </span>
                </div>
                <span class="text-xs text-gray-400">{{ $message->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="text-sm text-gray-700 whitespace-pre-wrap break-words max-h-40 overflow-y-auto">{{ Str::limit($message->content, 500) }}</div>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-sm text-gray-500">No messages found.</div>
        @endforelse
    </div>

    @if($messages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $messages->links() }}
    </div>
    @endif
</div>
@endsection
