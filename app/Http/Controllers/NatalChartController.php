<?php

namespace App\Http\Controllers;

use App\Models\NatalChart;
use App\Rules\ValidYandexCaptcha;
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

    public function processAsync(Request $Request)
    {
        $validated = $Request->validate([
            'captcha_token' => ['required', new ValidYandexCaptcha()],
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required|in:male,female',
            'purpose' => 'required|in:love,career,health,finance,personal,general',
            'birth_date' => 'required|date',
            'birth_time' => 'nullable|date_format:H:i',
            'city_id' => 'required|exists:cities,id',
            'marketing_consent' => 'nullable|boolean',
        ]);

        $isAuthenticated = Auth::check();
        $authenticatedUser = $isAuthenticated ? Auth::user() : null;

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

        if ($isAuthenticated && $authenticatedUser) {
            // Delete existing chart - user can only have ONE chart
            $authenticatedUser->natalCharts()->delete();

            // User is logged in - create chart directly
            $chart = NatalChart::create([
                'user_id' => $authenticatedUser->id,
                'name' => $authenticatedUser->name . "'s Chart",
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
                'access_token' => null, // No token needed - user is logged in
            ]);

            // Redirect to welcome with success message
            return response()->json([
                'success' => true,
                'redirect' => route('welcome') . '?chart_created=1',
            ]);
        }

        // User is NOT logged in - find or create user and send magic link
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make(Str::random(16)),
                'marketing_consent' => $validated['marketing_consent'] ?? false,
            ]
        );

        // Update marketing consent if user already exists
        if (!$user->wasRecentlyCreated && isset($validated['marketing_consent'])) {
            $user->marketing_consent = $validated['marketing_consent'];
            $user->save();
        }

        // Delete existing charts - user can only have ONE chart
        $user->natalCharts()->delete();

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
            'access_token' => Str::random(64),
        ]);

        // Send Email
        try {
            Mail::to($user->email)->send(new ChartReady($chart));
        } catch (\Exception $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }

    public function accessViaToken(string $token)
    {
        $chart = NatalChart::where('access_token', $token)->firstOrFail();
        $user = $chart->user;

        // Mark email as verified on first access
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Delete all other charts - user can only have ONE chart
        $user->natalCharts()->where('id', '!=', $chart->id)->delete();

        // Log user in and redirect to chart
        Auth::login($user);
        return redirect()->route('charts.show', $chart);
    }

    /**
     * Preview chart without login (after calculation).
     */
    public function preview(string $token)
    {
        $chart = NatalChart::where('access_token', $token)->firstOrFail();

        $templates = $this->getChatTemplates();
        $chatMessages = $chart->chatMessages()->get();

        return view('charts.show', [
            'chart' => $chart,
            'templates' => $templates,
            'chatMessages' => $chatMessages,
            'showEmailBanner' => true,
        ]);
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
