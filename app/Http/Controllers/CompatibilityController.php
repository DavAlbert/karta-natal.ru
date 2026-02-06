<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\CompatibilityResult;
use App\Rules\ValidHCaptcha;
use App\Services\AstrologyCalculationService;
use App\Services\AiAstrologyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\CompatibilityReady;

class CompatibilityController extends Controller
{
    protected $calcService;
    protected $aiService;

    public function __construct()
    {
        $this->calcService = new AstrologyCalculationService();
        $this->aiService = new AiAstrologyService();
    }

    /**
     * Show the compatibility form.
     */
    public function index()
    {
        return view('compatibility.index');
    }

    /**
     * Process the compatibility calculation.
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'hcaptcha_token' => ['required', new ValidHCaptcha()],

            // Person 1
            'person1_name' => 'required|string|max:255',
            'person1_email' => 'required|email|max:255',
            'person1_gender' => 'required|in:male,female',
            'person1_birth_date' => 'required|date',
            'person1_birth_time' => 'nullable|date_format:H:i',
            'person1_city_id' => 'required|exists:cities,id',

            // Person 2 (no name needed)
            'person2_gender' => 'required|in:male,female',
            'person2_birth_date' => 'required|date',
            'person2_birth_time' => 'nullable|date_format:H:i',
            'person2_city_id' => 'required|exists:cities,id',

            'marketing_consent' => 'nullable|boolean',
        ]);

        // Get cities
        $city1 = City::findOrFail($validated['person1_city_id']);
        $city2 = City::findOrFail($validated['person2_city_id']);

        // Calculate both natal charts
        $chart1 = $this->calcService->calculate(
            $validated['person1_birth_date'],
            $validated['person1_birth_time'] ?? '12:00',
            $city1->latitude,
            $city1->longitude,
            $city1->timezone_gmt ?? 0
        );

        $chart2 = $this->calcService->calculate(
            $validated['person2_birth_date'],
            $validated['person2_birth_time'] ?? '12:00',
            $city2->latitude,
            $city2->longitude,
            $city2->timezone_gmt ?? 0
        );

        // Calculate synastry aspects between charts
        $synastry = $this->calculateSynastry($chart1, $chart2);

        // Generate comprehensive AI compatibility report with structured outputs
        $aiReport = $this->aiService->generateCompatibilityReport($chart1, $chart2, $synastry);

        // Create combined result data
        $resultData = [
            'person1' => [
                'name' => $validated['person1_name'],
                'gender' => $validated['person1_gender'],
                'birth_date' => $validated['person1_birth_date'],
                'birth_time' => $validated['person1_birth_time'] ?? '12:00',
                'birth_place' => $city1->name,
            ],
            'person2' => [
                'gender' => $validated['person2_gender'],
                'birth_date' => $validated['person2_birth_date'],
                'birth_time' => $validated['person2_birth_time'] ?? '12:00',
                'birth_place' => $city2->name,
            ],
            'chart1' => $chart1,
            'chart2' => $chart2,
            'synastry' => $synastry,
            'ai_report' => $aiReport,
        ];

        // Handle user (same flow as natal chart)
        $isAuthenticated = Auth::check();
        $authenticatedUser = $isAuthenticated ? Auth::user() : null;

        $accessToken = Str::random(64);

        if ($isAuthenticated && $authenticatedUser) {
            CompatibilityResult::create([
                'user_id' => $authenticatedUser->id,
                'result_id' => Str::random(12),
                'access_token' => $accessToken,
                'person1_name' => $validated['person1_name'],
                'person1_email' => $validated['person1_email'],
                'person2_data' => [
                    'gender' => $validated['person2_gender'],
                    'birth_date' => $validated['person2_birth_date'],
                    'birth_time' => $validated['person2_birth_time'],
                    'birth_place' => $city2->name,
                ],
                'result_data' => $resultData,
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('compatibility.result', $accessToken),
            ]);
        }

        // User NOT logged in - find or create and send magic link
        $user = \App\Models\User::firstOrCreate(
            ['email' => $validated['person1_email']],
            [
                'name' => $validated['person1_name'],
                'password' => Hash::make(Str::random(16)),
                'marketing_consent' => $validated['marketing_consent'] ?? false,
            ]
        );

        if (!$user->wasRecentlyCreated && isset($validated['marketing_consent'])) {
            $user->marketing_consent = $validated['marketing_consent'];
            $user->save();
        }

        CompatibilityResult::create([
            'user_id' => $user->id,
            'result_id' => Str::random(12),
            'access_token' => $accessToken,
            'person1_name' => $validated['person1_name'],
            'person1_email' => $validated['person1_email'],
            'person2_data' => [
                'gender' => $validated['person2_gender'],
                'birth_date' => $validated['person2_birth_date'],
                'birth_time' => $validated['person2_birth_time'],
                'birth_place' => $city2->name,
            ],
            'result_data' => $resultData,
        ]);

        // Send email
        try {
            Mail::to($user->email)->send(new CompatibilityReady($user, $accessToken));
        } catch (\Exception $e) {
            \Log::error('Compatibility email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'email_sent' => true]);
    }

    /**
     * Show compatibility result.
     */
    public function result(string $token)
    {
        $result = CompatibilityResult::where('access_token', $token)->firstOrFail();

        $user = $result->user;
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        Auth::login($user);

        return view('compatibility.result', ['result' => $result]);
    }

    /**
     * Shared result (no login).
     */
    public function shared(string $token)
    {
        $result = CompatibilityResult::where('access_token', $token)->firstOrFail();
        return view('compatibility.shared', ['result' => $result]);
    }

    /**
     * Calculate synastry between two natal charts.
     */
    protected function calculateSynastry(array $chart1, array $chart2): array
    {
        $synastry = [];
        $aspectRules = [
            'Соединение' => ['angle' => 0, 'orb' => 8, 'power' => 10],
            'Оппозиция' => ['angle' => 180, 'orb' => 8, 'power' => 8],
            'Трин' => ['angle' => 120, 'orb' => 8, 'power' => 10],
            'Квадрат' => ['angle' => 90, 'orb' => 7, 'power' => 6],
            'Секстиль' => ['angle' => 60, 'orb' => 6, 'power' => 8],
        ];

        $keyPlanets = ['sun', 'moon', 'mercury', 'venus', 'mars', 'jupiter', 'saturn', 'north_node'];

        $harmony = 0;
        $tension = 0;
        $totalAspects = 0;

        foreach ($keyPlanets as $planet) {
            if (!isset($chart1['planets'][$planet]) || !isset($chart2['planets'][$planet])) continue;

            $p1 = $chart1['planets'][$planet];
            $p2 = $chart2['planets'][$planet];

            if (!isset($p1['absolute_degree']) || !isset($p2['absolute_degree'])) continue;

            $deg1 = $p1['absolute_degree'];
            $deg2 = $p2['absolute_degree'];

            $diff = abs($deg1 - $deg2);
            if ($diff > 180) $diff = 360 - $diff;

            foreach ($aspectRules as $type => $rule) {
                $orb = abs($diff - $rule['angle']);
                if ($orb <= $rule['orb']) {
                    $nature = in_array($type, ['Квадрат', 'Оппозиция']) ? 'tension' : 'harmony';
                    $power = $rule['power'] * (1 - $orb / $rule['orb']);

                    if ($nature === 'harmony') $harmony += $power;
                    else $tension += $power;

                    $synastry['aspects'][] = [
                        'planet1' => $p1['name'] ?? ucfirst($planet),
                        'planet2' => $p2['name'] ?? ucfirst($planet),
                        'type' => $type,
                        'orb' => round($orb, 1),
                        'nature' => $nature,
                        'power' => round($power, 1),
                    ];

                    $totalAspects++;
                    break;
                }
            }
        }

        usort($synastry['aspects'] ?? [], fn($a, $b) => $b['power'] <=> $a['power']);

        // Calculate overall score
        $score = 50;
        if ($totalAspects > 0) {
            $balance = ($harmony - $tension) / ($harmony + $tension + 1);
            $score = 50 + ($balance * 40) + min(10, $totalAspects);
        }
        $score = max(20, min(98, round($score)));

        return [
            'aspects' => $synastry['aspects'] ?? [],
            'score' => $score,
            'harmony' => round($harmony, 1),
            'tension' => round($tension, 1),
            'total_aspects' => $totalAspects,
        ];
    }
}
