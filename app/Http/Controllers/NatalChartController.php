<?php

namespace App\Http\Controllers;

use App\Models\NatalChart;
use App\Services\AstrologyCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ChartReady;

class NatalChartController extends Controller
{
    public function index()
    {
        $charts = Auth::user()->natalCharts()->latest()->get();
        return view('dashboard', compact('charts'));
    }

    public function processAsync(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'birth_date' => 'required|date',
            'birth_time' => 'nullable|date_format:H:i',
            'birth_place' => 'required|string|max:255',
        ]);

        // Find or Create User
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make(Str::random(16)), // Random password user won't know initially
            ]
        );

        // Auto-login to allow viewing the chart immediately
        Auth::login($user);

        // Calculate Data (Mock for now)
        $service = new AstrologyCalculationService();
        $chartData = $service->calculate(
            $validated['birth_date'],
            $validated['birth_time'] ?? '12:00', // Default if not provided
            55.75,
            37.61 // Default Lat/Lon
        );

        // Create Chart
        $chart = NatalChart::create([
            'user_id' => $user->id,
            'name' => $validated['name'] . "'s Chart",
            'birth_date' => $validated['birth_date'],
            'birth_time' => $validated['birth_time'],
            'birth_place' => $validated['birth_place'],
            'latitude' => 55.75, // Placeholder
            'longitude' => 37.61, // Placeholder
            'timezone' => 'UTC',
            'chart_data' => $chartData,
            'type' => 'natal',
        ]);

        // Send Email
        try {
            Mail::to($user->email)->send(new ChartReady($chart));
        } catch (\Exception $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Chart ready!',
            'redirect_url' => route('charts.show', $chart)
        ]);
    }

    public function show(NatalChart $natalChart)
    {
        // Ensure user owns the chart
        if ($natalChart->user_id !== Auth::id()) {
            abort(403);
        }

        return view('charts.show', ['chart' => $natalChart]);
    }

    public function generateReport(NatalChart $natalChart)
    {
        if ($natalChart->user_id !== Auth::id()) {
            abort(403);
        }

        if ($natalChart->report_status !== 'processing') {
            \App\Jobs\GenerateAiReport::dispatch($natalChart);
        }

        return redirect()->route('charts.show', $natalChart)
            ->with('status', 'AI Report generation started!');
    }

    public function checkStatus(NatalChart $natalChart)
    {
        if ($natalChart->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $natalChart->report_status,
        ]);
    }
}
