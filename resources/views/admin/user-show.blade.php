@extends('admin.layout')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to users</a>
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-900">{{ $user->name ?: '(no name)' }}</h1>
    </div>
    <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="text-sm text-gray-500">Email</div>
            <div class="text-sm font-medium">{{ $user->email }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Registered</div>
            <div class="text-sm font-medium">{{ $user->created_at->format('M d, Y H:i') }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Locale</div>
            <div class="text-sm font-medium">{{ $user->locale ?? '—' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Marketing Consent</div>
            <div class="text-sm font-medium">{{ $user->marketing_consent ? 'Yes' : 'No' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Email Verified</div>
            <div class="text-sm font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'No' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Admin</div>
            <div class="text-sm font-medium">{{ $user->is_admin ? 'Yes' : 'No' }}</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Charts ({{ $user->natalCharts->count() }})</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Birth Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Birth Place</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chart Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($user->natalCharts as $chart)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chart->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chart->birth_date?->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chart->birth_place }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $chart->chart_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $chart->chart_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                        {{ in_array($chart->chart_status, ['pending', 'processing']) ? 'bg-yellow-100 text-yellow-800' : '' }}
                    ">{{ $chart->chart_status }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $chart->report_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $chart->report_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                        {{ in_array($chart->report_status, ['pending', 'processing']) ? 'bg-yellow-100 text-yellow-800' : '' }}
                    ">{{ $chart->report_status ?? 'none' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($chart->chat_messages_count > 0)
                        <a href="{{ route('admin.conversation', $chart) }}" class="text-blue-600 hover:underline">{{ $chart->chat_messages_count }} messages</a>
                    @else
                        <span class="text-gray-400">0</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $chart->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No charts.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
