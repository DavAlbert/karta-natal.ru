<?php

namespace App\Http\Controllers;

use App\Models\NatalChart;
use App\Rules\ValidTurnstile;
use App\Services\AstrologyCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ChartReady;
use App\Jobs\ProcessNatalChartJob;

class NatalChartController extends Controller
{
    public function index()
    {
        $charts = Auth::user()->natalCharts()->latest()->get();

        $horoscope = null;
        $sunSignKey = null;
        if ($charts->isNotEmpty()) {
            $chart = $charts->first();
            $russianSunSign = $chart->chart_data['planets']['sun']['sign'] ?? null;
            $signToKey = [
                'Овен' => 'aries', 'Телец' => 'taurus', 'Близнецы' => 'gemini',
                'Рак' => 'cancer', 'Лев' => 'leo', 'Дева' => 'virgo',
                'Весы' => 'libra', 'Скорпион' => 'scorpio', 'Стрелец' => 'sagittarius',
                'Козерог' => 'capricorn', 'Водолей' => 'aquarius', 'Рыбы' => 'pisces',
            ];
            $sunSignKey = $signToKey[$russianSunSign] ?? null;
            if ($sunSignKey) {
                $locale = app()->getLocale();
                $horoscope = \App\Models\DailyHoroscope::where('sign', $sunSignKey)
                    ->where('locale', $locale)
                    ->first();
            }
        }

        return view('dashboard', compact('charts', 'horoscope', 'sunSignKey'));
    }

    public function processAsync(Request $Request)
    {
        $emailRules = ['required', 'email', 'max:255'];
        if (!Auth::check()) {
            $emailRules[] = 'unique:users,email';
        }

        $validated = $Request->validate([
            'cf_turnstile_response' => ['required', new ValidTurnstile()],
            'name' => 'required|string|max:255',
            'email' => $emailRules,
            'gender' => 'required|in:male,female',
            'purpose' => 'required|in:love,career,health,finance,personal,general',
            'birth_date' => 'required|date',
            'birth_time' => 'nullable|date_format:H:i',
            'city_id' => 'required|exists:cities,id',
            'marketing_consent' => 'nullable|boolean',
            'locale' => 'nullable|string|in:' . implode(',', config('app.available_locales', ['en'])),
        ], [
            'email.unique' => __('common.email_already_used'),
        ]);

        $locale = $validated['locale'] ?? app()->getLocale();

        $isAuthenticated = Auth::check();
        $authenticatedUser = $isAuthenticated ? Auth::user() : null;

        // Get city from database
        $city = \App\Models\City::findOrFail($validated['city_id']);

        // Calculate chart data synchronously (fast operation)
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
                'chart_status' => 'completed',
                'report_status' => 'new',
                'type' => 'natal',
                'access_token' => null,
                'locale' => $locale,
            ]);

            // Dispatch AI report generation job asynchronously
            ProcessNatalChartJob::dispatch($chart, false, true);

            return response()->json([
                'success' => true,
                'redirect' => route('welcome') . '?chart_created=1',
            ]);
        }

        // User is NOT logged in - create new user (email uniqueness enforced by validation)
        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'password' => Hash::make(Str::random(16)),
            'marketing_consent' => $validated['marketing_consent'] ?? false,
            'locale' => $locale,
        ]);

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
            'chart_status' => 'completed',
            'report_status' => 'new',
            'type' => 'natal',
            'access_token' => Str::random(64),
            'locale' => $locale,
        ]);

        // Dispatch AI report generation job + send email after completion
        ProcessNatalChartJob::dispatch($chart, true, true);

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
        return redirect('/charts/' . $chart->id);
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

        return redirect(locale_route('charts.show', ['natalChart' => $natalChart]))->with('status', 'Password set successfully!');
    }

    public function show(NatalChart $natalChart)
    {
        // Ensure user owns the chart
        if ($natalChart->user_id !== Auth::id()) {
            abort(403);
        }

        $templates = $this->getChatTemplates();
        $chatMessages = $natalChart->chatMessages()->get();

        // Fetch daily horoscope for this chart's sun sign
        $horoscope = null;
        $sunSignKey = null;
        $russianSunSign = $natalChart->chart_data['planets']['sun']['sign'] ?? null;
        if ($russianSunSign) {
            $signToKey = [
                'Овен' => 'aries', 'Телец' => 'taurus', 'Близнецы' => 'gemini',
                'Рак' => 'cancer', 'Лев' => 'leo', 'Дева' => 'virgo',
                'Весы' => 'libra', 'Скорпион' => 'scorpio', 'Стрелец' => 'sagittarius',
                'Козерог' => 'capricorn', 'Водолей' => 'aquarius', 'Рыбы' => 'pisces',
            ];
            $sunSignKey = $signToKey[$russianSunSign] ?? null;
            if ($sunSignKey) {
                $horoscope = \App\Models\DailyHoroscope::where('sign', $sunSignKey)
                    ->where('locale', app()->getLocale())
                    ->first();
            }
        }

        return view('charts.show', [
            'chart' => $natalChart,
            'templates' => $templates,
            'chatMessages' => $chatMessages,
            'horoscope' => $horoscope,
            'sunSignKey' => $sunSignKey,
        ]);
    }

    protected function getChatTemplates(): array
    {
        return [
            'character' => [
                'title' => __('astrology.chat_category_general'),
                'icon' => '👤',
                'prompt' => __('astrology.chat_template_general'),
            ],
            'love' => [
                'title' => __('astrology.chat_category_love'),
                'icon' => '💕',
                'prompt' => __('astrology.chat_template_love'),
            ],
            'career' => [
                'title' => __('astrology.chat_category_career'),
                'icon' => '💼',
                'prompt' => __('astrology.chat_template_career'),
            ],
            'finance' => [
                'title' => __('astrology.chat_category_finance'),
                'icon' => '💰',
                'prompt' => __('astrology.chat_template_finance'),
            ],
            'health' => [
                'title' => __('astrology.chat_category_health'),
                'icon' => '🏥',
                'prompt' => __('astrology.chat_template_health'),
            ],
            'karmic' => [
                'title' => __('astrology.chat_category_karma'),
                'icon' => '✨',
                'prompt' => __('astrology.chat_template_karma'),
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

        return redirect(locale_route('charts.show', ['natalChart' => $natalChart]))
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
