<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NatalChart;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'users_this_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'users_this_month' => User::where('created_at', '>=', now()->subMonth())->count(),
            'total_charts' => NatalChart::count(),
            'charts_today' => NatalChart::whereDate('created_at', today())->count(),
            'charts_completed' => NatalChart::where('chart_status', 'completed')->count(),
            'charts_processing' => NatalChart::whereIn('chart_status', ['pending', 'processing'])->count(),
            'charts_failed' => NatalChart::where('chart_status', 'failed')->count(),
            'reports_completed' => NatalChart::where('report_status', 'completed')->count(),
            'total_messages' => ChatMessage::count(),
        ];

        $recentUsers = User::latest()->limit(10)->get();
        $recentCharts = NatalChart::with('user')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCharts'));
    }

    public function users(Request $request)
    {
        $query = User::withCount('natalCharts');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(25)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function userShow(User $user)
    {
        $user->load(['natalCharts' => fn($q) => $q->withCount('chatMessages')]);

        return view('admin.user-show', compact('user'));
    }

    public function charts(Request $request)
    {
        $query = NatalChart::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('birth_place', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('chart_status', $status);
        }

        $charts = $query->latest()->paginate(25)->withQueryString();

        return view('admin.charts', compact('charts'));
    }

    public function conversation(NatalChart $natalChart)
    {
        $natalChart->load(['user', 'chatMessages']);

        return view('admin.conversation', compact('natalChart'));
    }

    public function messages(Request $request)
    {
        $query = ChatMessage::with('natalChart.user');

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->get('search')) {
            $query->where('content', 'like', "%{$search}%");
        }

        $messages = $query->latest()->paginate(50)->withQueryString();

        return view('admin.messages', compact('messages'));
    }
}
