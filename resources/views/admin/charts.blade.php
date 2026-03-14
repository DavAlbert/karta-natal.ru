@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Charts ({{ $charts->total() }})</h1>
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <form method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or birth place..."
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">All statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.charts') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
            @endif
        </form>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Birth Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Birth Place</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chart</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($charts as $chart)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chart->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    @if($chart->user)
                        <a href="{{ route('admin.users.show', $chart->user) }}" class="text-blue-600 hover:underline">{{ $chart->user->email }}</a>
                    @else
                        Guest
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chart->birth_date?->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chart->birth_place }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chart->type ?? 'natal' }}</td>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $chart->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No charts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($charts->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $charts->links() }}
    </div>
    @endif
</div>
@endsection
