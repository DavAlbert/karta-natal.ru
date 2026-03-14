@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">Total Users</div>
        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</div>
        <div class="text-sm text-gray-400 mt-1">+{{ $stats['users_today'] }} today</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">Users This Week</div>
        <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['users_this_week']) }}</div>
        <div class="text-sm text-gray-400 mt-1">{{ $stats['users_this_month'] }} this month</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">Total Charts</div>
        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_charts']) }}</div>
        <div class="text-sm text-gray-400 mt-1">+{{ $stats['charts_today'] }} today</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-sm font-medium text-gray-500">AI Reports</div>
        <div class="text-3xl font-bold text-green-600">{{ number_format($stats['reports_completed']) }}</div>
        <div class="text-sm text-gray-400 mt-1">{{ $stats['total_messages'] }} chat messages</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Charts Completed</div>
        <div class="text-xl font-bold text-green-600">{{ $stats['charts_completed'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Charts Processing</div>
        <div class="text-xl font-bold text-yellow-600">{{ $stats['charts_processing'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Charts Failed</div>
        <div class="text-xl font-bold text-red-600">{{ $stats['charts_failed'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
            <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentUsers as $user)
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $user->name ?: '(no name)' }}</div>
                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                </div>
                <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <div class="px-6 py-4 text-sm text-gray-500">No users yet.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent Charts</h2>
            <a href="{{ route('admin.charts') }}" class="text-sm text-blue-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentCharts as $chart)
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $chart->name }}</div>
                    <div class="text-sm text-gray-500">{{ $chart->user?->email ?? 'Guest' }} &middot; {{ $chart->birth_place }}</div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $chart->chart_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $chart->chart_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                        {{ in_array($chart->chart_status, ['pending', 'processing']) ? 'bg-yellow-100 text-yellow-800' : '' }}
                    ">{{ $chart->chart_status }}</span>
                    <div class="text-xs text-gray-400 mt-1">{{ $chart->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="px-6 py-4 text-sm text-gray-500">No charts yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
