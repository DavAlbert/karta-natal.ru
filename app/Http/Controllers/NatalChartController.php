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
            'gender' => 'required|in:male,female',
            'purpose' => 'required|in:love,career,health,finance,personal,general',
            'birth_date' => 'required|date',
            'birth_time' => 'nullable|date_format:H:i',
            'city_id' => 'required|exists:cities,id',
            'marketing_consent' => 'nullable|boolean',
        ]);

        // Find or Create User
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make(Str::random(16)), // Random password user won't know initially
                'marketing_consent' => $validated['marketing_consent'] ?? false,
            ]
        );

        // Update marketing consent if user already exists
        if (!$user->wasRecentlyCreated && isset($validated['marketing_consent'])) {
            $user->marketing_consent = $validated['marketing_consent'];
            $user->save();
        }

        // DO NOT auto-login - user must access via email link

        // Get city from database
        $city = \App\Models\City::findOrFail($validated['city_id']);

        // Calculate Data with city coordinates and timezone
        $service = new AstrologyCalculationService();
        $chartData = $service->calculate(
            $validated['birth_date'],
            $validated['birth_time'] ?? '12:00',
            $city->latitude,
            $city->longitude,
            $city->timezone_gmt ?? 0
        );

        // Create Chart with access token
        $chart = NatalChart::create([
            'user_id' => $user->id,
            'name' => $validated['name'] . "'s Chart",
            'gender' => $validated['gender'],
            'purpose' => $validated['purpose'],
            'birth_date' => $validated['birth_date'],
            'birth_time' => $validated['birth_time'],
            'birth_place' => $city->name,
            'city_id' => $city->id,
            'latitude' => $city->latitude,
            'longitude' => $city->longitude,
            'timezone' => 'GMT+' . $city->timezone_gmt,
            'chart_data' => $chartData,
            'type' => 'natal',
            'access_token' => Str::random(64), // Secure token for email access
        ]);

        // Send Email
        try {
            Mail::to($user->email)->send(new ChartReady($chart));
        } catch (\Exception $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Chart calculated! Check your email for the access link.',
        ]);
    }

    public function accessViaToken(string $token)
    {
        $chart = NatalChart::where('access_token', $token)->firstOrFail();
        $user = $chart->user;

        // Check if user has set a password (password is not random)
        // We'll use a simple check: if password was created recently and user never logged in
        // Better approach: add a 'password_set' boolean to users table
        // For now, we'll redirect all first-time token users to password setup

        if (!$user->email_verified_at) {
            // First time access - redirect to password setup
            return redirect()->route('charts.set-password', ['natalChart' => $chart->id, 'token' => $token]);
        }

        // User has already set password, just log them in
        Auth::login($user);
        return redirect()->route('charts.show', $chart);
    }

    public function showSetPassword(NatalChart $natalChart, Request $request)
    {
        $token = $request->query('token');

        // Verify token matches
        if ($natalChart->access_token !== $token) {
            abort(403, 'Invalid access token');
        }

        return view('auth.set-password', compact('natalChart', 'token'));
    }

    public function storePassword(NatalChart $natalChart, Request $request)
    {
        $token = $request->input('token');

        // Verify token
        if ($natalChart->access_token !== $token) {
            abort(403, 'Invalid access token');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $natalChart->user;
        $user->password = Hash::make($validated['password']);
        $user->email_verified_at = now(); // Mark as verified
        $user->save();

        // Clear the access token (one-time use)
        $natalChart->access_token = null;
        $natalChart->save();

        // Log in the user
        Auth::login($user);

        return redirect()->route('charts.show', $natalChart)->with('status', 'Password set successfully!');
    }

    public function show(NatalChart $natalChart)
    {
        // Ensure user owns the chart
        if ($natalChart->user_id !== Auth::id()) {
            abort(403);
        }

        $templates = $this->getChatTemplates();
        $chatMessages = $natalChart->chatMessages()->get();

        return view('charts.show', [
            'chart' => $natalChart,
            'templates' => $templates,
            'chatMessages' => $chatMessages,
        ]);
    }

    protected function getChatTemplates(): array
    {
        return [
            'character' => [
                'title' => 'Общая характеристика',
                'icon' => '👤',
                'prompt' => 'Расскажи подробно о моей личности на основе моей натальной карты.',
            ],
            'love' => [
                'title' => 'Любовь',
                'icon' => '💕',
                'prompt' => 'Проанализируй мою карту в вопросах любви и отношений.',
            ],
            'career' => [
                'title' => 'Карьера',
                'icon' => '💼',
                'prompt' => 'Расскажи о моём карьерном потенциале и призвании.',
            ],
            'finance' => [
                'title' => 'Финансы',
                'icon' => '💰',
                'prompt' => 'Проанализируй мою карту в вопросах финансов.',
            ],
            'health' => [
                'title' => 'Здоровье',
                'icon' => '🏥',
                'prompt' => 'Расскажи о моём здоровье на основе карты.',
            ],
            'karmic' => [
                'title' => 'Карма',
                'icon' => '✨',
                'prompt' => 'Расскажи о моём кармическом пути.',
            ],
        ];
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
