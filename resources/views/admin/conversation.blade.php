@extends('admin.layout')

@section('content')
<div class="mb-6">
    @if($natalChart->user)
        <a href="{{ route('admin.users.show', $natalChart->user) }}" class="text-sm text-blue-600 hover:underline">&larr; Back to {{ $natalChart->user->email }}</a>
    @else
        <a href="{{ route('admin.charts') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to charts</a>
    @endif
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-900">Conversation: {{ $natalChart->name }}</h1>
        <div class="text-sm text-gray-500 mt-1">
            {{ $natalChart->user?->email ?? 'Guest' }} &middot;
            {{ $natalChart->birth_place }} &middot;
            {{ $natalChart->birth_date?->format('M d, Y') }} &middot;
            {{ $natalChart->chatMessages->count() }} messages
        </div>
    </div>

    <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
        @forelse($natalChart->chatMessages as $message)
        <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-2xl rounded-lg px-4 py-3 {{ $message->role === 'user' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-900' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold {{ $message->role === 'user' ? 'text-blue-600' : 'text-green-600' }}">
                        {{ $message->role === 'user' ? ($natalChart->user?->name ?: 'User') : 'Assistant' }}
                    </span>
                    <span class="text-xs text-gray-400 ml-4">{{ $message->created_at->format('M d, H:i') }}</span>
                </div>
                <div class="text-sm whitespace-pre-wrap break-words">{{ $message->content }}</div>
            </div>
        </div>
        @empty
        <div class="text-center text-sm text-gray-500 py-8">No messages in this conversation.</div>
        @endforelse
    </div>
</div>
@endsection
