<?php

namespace App\Http\Controllers;

use App\Mail\PartnerInvitation;
use App\Models\City;
use App\Models\NatalChart;
use App\Models\PartnerCompatibility;
use App\Models\User;
use App\Rules\ValidYandexCaptcha;
use App\Services\AiAstrologyService;
use App\Services\AstrologyCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerCompatibilityController extends Controller
{
    protected AstrologyCalculationService $calcService;
    protected AiAstrologyService $aiService;

    public function __construct()
    {
        $this->calcService = new AstrologyCalculationService();
        $this->aiService = new AiAstrologyService();
    }

    /**
     * Store new compatibility calculation (from Dashboard tab)
     */
    public function store(Request $request, NatalChart $natalChart): JsonResponse
    {
        // Verify ownership
        if ($natalChart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'captcha_token' => ['required', new ValidYandexCaptcha()],
            'partner_name' => 'required|string|max:255',
            'partner_email' => 'required|email|max:255',
            'partner_gender' => 'required|in:male,female',
            'partner_birth_date' => 'required|date',
            'partner_birth_time' => 'nullable|date_format:H:i',
            'partner_city_id' => 'required|exists:cities,id',
        ]);

        // Get partner city
        $partnerCity = City::findOrFail($validated['partner_city_id']);

        // Calculate partner's natal chart data (pre-calculate for synastry)
        $partnerChartData = $this->calcService->calculate(
            $validated['partner_birth_date'],
            $validated['partner_birth_time'] ?? '12:00',
            $partnerCity->latitude,
            $partnerCity->longitude,
            $partnerCity->timezone_gmt ?? 0
        );

        // Calculate synastry between user's chart and partner's chart
        $userChartData = $natalChart->chart_data;
        $synastry = $this->calculateSynastry($userChartData, $partnerChartData);

        // Calculate 16 category scores
        $scores = $this->calculateCategoryScores($userChartData, $partnerChartData, $synastry);

        // Generate AI report
        $aiReport = $this->generateAiReport($userChartData, $partnerChartData, $synastry, $scores);

        // Create verification token
        $verificationToken = Str::random(64);

        // Create the compatibility record
        $compatibility = PartnerCompatibility::create([
            'user_id' => Auth::id(),
            'user_chart_id' => $natalChart->id,
            'partner_name' => $validated['partner_name'],
            'partner_email' => $validated['partner_email'],
            'partner_gender' => $validated['partner_gender'],
            'partner_birth_date' => $validated['partner_birth_date'],
            'partner_birth_time' => $validated['partner_birth_time'],
            'partner_city_id' => $validated['partner_city_id'],
            'verification_token' => $verificationToken,
            'synastry_data' => $synastry,
            'scores' => $scores,
            'ai_report' => $aiReport,
            'status' => 'pending',
        ]);

        // Send invitation email to partner
        try {
            Mail::to($validated['partner_email'])->send(new PartnerInvitation($compatibility));
        } catch (\Exception $e) {
            Log::error('Partner invitation email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Приглашение отправлено партнёру на ' . $validated['partner_email'],
            'compatibility_id' => $compatibility->id,
        ]);
    }

    /**
     * Show verification page for partner
     */
    public function verify(string $token): View
    {
        $compatibility = PartnerCompatibility::where('verification_token', $token)
            ->with(['user', 'userChart', 'partnerCity'])
            ->firstOrFail();

        // Calculate partner's natal chart for display
        $partnerCity = $compatibility->partnerCity;
        $partnerChartData = $this->calcService->calculate(
            $compatibility->partner_birth_date->format('Y-m-d'),
            $compatibility->partner_birth_time ?? '12:00',
            $partnerCity->latitude,
            $partnerCity->longitude,
            $partnerCity->timezone_gmt ?? 0
        );

        return view('compatibility.verify', [
            'compatibility' => $compatibility,
            'partnerChartData' => $partnerChartData,
            'initiatorName' => $compatibility->user->name ?? 'Ваш партнёр',
        ]);
    }

    /**
     * Partner confirms and gains access to result
     */
    public function confirm(Request $request, string $token): JsonResponse
    {
        $compatibility = PartnerCompatibility::where('verification_token', $token)->firstOrFail();

        if ($compatibility->isVerified()) {
            $partnerChart = $compatibility->partnerChart;
            Auth::login($compatibility->partnerUser);
            return response()->json([
                'success' => true,
                'message' => 'Уже подтверждено',
                'redirect' => $partnerChart ? route('charts.show', $partnerChart) . '?tab=compatibility' : route('welcome'),
            ]);
        }

        $marketingConsent = $request->input('marketing_consent', false);

        // Create or find partner user
        $partnerUser = User::firstOrCreate(
            ['email' => $compatibility->partner_email],
            [
                'name' => $compatibility->partner_name,
                'password' => Hash::make(Str::random(16)),
                'marketing_consent' => $marketingConsent,
            ]
        );

        // Update marketing consent if user already exists
        if (!$partnerUser->wasRecentlyCreated) {
            $partnerUser->marketing_consent = $marketingConsent;
            $partnerUser->save();
        }

        // Create partner's natal chart
        $partnerCity = $compatibility->partnerCity;
        $partnerChartData = $this->calcService->calculate(
            $compatibility->partner_birth_date->format('Y-m-d'),
            $compatibility->partner_birth_time ?? '12:00',
            $partnerCity->latitude,
            $partnerCity->longitude,
            $partnerCity->timezone_gmt ?? 0
        );

        $partnerChart = NatalChart::create([
            'user_id' => $partnerUser->id,
            'name' => $compatibility->partner_name,
            'gender' => $compatibility->partner_gender,
            'birth_date' => $compatibility->partner_birth_date,
            'birth_time' => $compatibility->partner_birth_time,
            'birth_place' => $partnerCity->name_ru ?? $partnerCity->name,
            'city_id' => $compatibility->partner_city_id,
            'latitude' => $partnerCity->latitude,
            'longitude' => $partnerCity->longitude,
            'timezone' => $partnerCity->timezone_gmt ?? 0,
            'chart_data' => $partnerChartData,
            'type' => 'natal',
            'access_token' => Str::random(64),
        ]);

        // Update compatibility record
        $compatibility->update([
            'partner_user_id' => $partnerUser->id,
            'partner_chart_id' => $partnerChart->id,
            'verified_at' => now(),
            'status' => 'completed',
        ]);

        // Log in the partner
        Auth::login($partnerUser);

        // Verify email if not already
        if (!$partnerUser->email_verified_at) {
            $partnerUser->email_verified_at = now();
            $partnerUser->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Подтверждено!',
            'redirect' => route('charts.show', $partnerChart) . '?tab=compatibility',
        ]);
    }

    /**
     * Get compatibility data for Dashboard tab (API)
     */
    public function show(NatalChart $natalChart): JsonResponse
    {
        if ($natalChart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userId = Auth::id();

        // Get existing compatibility - either as initiator or as partner
        $compatibility = PartnerCompatibility::where('user_chart_id', $natalChart->id)
            ->orWhere(function($q) use ($natalChart) {
                $q->where('partner_chart_id', $natalChart->id);
            })
            ->with(['partnerCity', 'partnerUser', 'user', 'userChart'])
            ->latest()
            ->first();

        if (!$compatibility) {
            return response()->json([
                'exists' => false,
            ]);
        }

        // Determine if current user is the initiator or the partner
        $isInitiator = $compatibility->user_id === $userId;
        $partnerName = $isInitiator ? $compatibility->partner_name : ($compatibility->user->name ?? 'Партнёр');
        $partnerEmail = $isInitiator ? $compatibility->partner_email : $compatibility->user->email;
        $partnerBirthDate = $isInitiator
            ? $compatibility->partner_birth_date->format('d.m.Y')
            : ($compatibility->userChart?->birth_date?->format('d.m.Y') ?? '');

        return response()->json([
            'exists' => true,
            'compatibility' => [
                'id' => $compatibility->id,
                'partner_name' => $partnerName,
                'partner_email' => $partnerEmail,
                'partner_birth_date' => $partnerBirthDate,
                'status' => $compatibility->status,
                'verified_at' => $compatibility->verified_at?->format('d.m.Y H:i'),
                'overall_score' => $compatibility->overall_score,
                'score_description' => $compatibility->score_description,
                'score_color' => $compatibility->score_color,
                'scores' => $compatibility->category_scores,
                'synastry' => $compatibility->synastry_data,
                'ai_report' => $compatibility->ai_report,
                'is_initiator' => $isInitiator,
            ],
        ]);
    }

    /**
     * Calculate synastry aspects between two charts
     */
    protected function calculateSynastry(array $chart1, array $chart2): array
    {
        $synastry = ['aspects' => []];
        $aspectRules = [
            'Соединение' => ['angle' => 0, 'orb' => 8, 'power' => 10],
            'Оппозиция' => ['angle' => 180, 'orb' => 8, 'power' => 8],
            'Трин' => ['angle' => 120, 'orb' => 8, 'power' => 10],
            'Квадрат' => ['angle' => 90, 'orb' => 7, 'power' => 6],
            'Секстиль' => ['angle' => 60, 'orb' => 6, 'power' => 8],
        ];

        // Extended planet list for synastry
        $keyPlanets = [
            'sun', 'moon', 'mercury', 'venus', 'mars',
            'jupiter', 'saturn', 'uranus', 'neptune', 'pluto',
            'north_node', 'chiron'
        ];

        $planetNames = [
            'sun' => 'Солнце', 'moon' => 'Луна', 'mercury' => 'Меркурий',
            'venus' => 'Венера', 'mars' => 'Марс', 'jupiter' => 'Юпитер',
            'saturn' => 'Сатурн', 'uranus' => 'Уран', 'neptune' => 'Нептун',
            'pluto' => 'Плутон', 'north_node' => 'Сев. узел', 'chiron' => 'Хирон',
        ];

        $harmony = 0;
        $tension = 0;
        $totalAspects = 0;

        // Cross-chart aspects (Planet1 from chart1 to Planet2 from chart2)
        foreach ($keyPlanets as $planet1) {
            foreach ($keyPlanets as $planet2) {
                if (!isset($chart1['planets'][$planet1]) || !isset($chart2['planets'][$planet2])) continue;

                $p1 = $chart1['planets'][$planet1];
                $p2 = $chart2['planets'][$planet2];

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

                        // Weight certain aspects more heavily
                        $weight = $this->getAspectWeight($planet1, $planet2);
                        $power *= $weight;

                        if ($nature === 'harmony') $harmony += $power;
                        else $tension += $power;

                        $synastry['aspects'][] = [
                            'planet1' => $planetNames[$planet1] ?? ucfirst($planet1),
                            'planet1_key' => $planet1,
                            'planet2' => $planetNames[$planet2] ?? ucfirst($planet2),
                            'planet2_key' => $planet2,
                            'type' => $type,
                            'orb' => round($orb, 1),
                            'nature' => $nature,
                            'power' => round($power, 1),
                            'description' => $this->getAspectDescription($planet1, $planet2, $type),
                        ];

                        $totalAspects++;
                        break;
                    }
                }
            }
        }

        // Sort by power
        usort($synastry['aspects'], fn($a, $b) => $b['power'] <=> $a['power']);

        // Calculate overall score
        $score = 50;
        if ($totalAspects > 0) {
            $balance = ($harmony - $tension) / ($harmony + $tension + 1);
            $score = 50 + ($balance * 40) + min(10, $totalAspects);
        }
        $score = max(20, min(98, round($score)));

        return [
            'aspects' => $synastry['aspects'],
            'score' => $score,
            'harmony' => round($harmony, 1),
            'tension' => round($tension, 1),
            'total_aspects' => $totalAspects,
        ];
    }

    /**
     * Get weight for aspect based on planets involved
     */
    protected function getAspectWeight(string $p1, string $p2): float
    {
        // Venus-Mars aspects (attraction)
        if (($p1 === 'venus' && $p2 === 'mars') || ($p1 === 'mars' && $p2 === 'venus')) {
            return 1.5;
        }
        // Sun-Moon aspects (core compatibility)
        if (($p1 === 'sun' && $p2 === 'moon') || ($p1 === 'moon' && $p2 === 'sun')) {
            return 1.4;
        }
        // Moon-Moon (emotional connection)
        if ($p1 === 'moon' && $p2 === 'moon') {
            return 1.3;
        }
        // Venus-Venus (values & love)
        if ($p1 === 'venus' && $p2 === 'venus') {
            return 1.2;
        }
        // Saturn aspects (long-term stability)
        if ($p1 === 'saturn' || $p2 === 'saturn') {
            return 1.1;
        }

        return 1.0;
    }

    /**
     * Get description for synastry aspect
     */
    protected function getAspectDescription(string $p1, string $p2, string $type): string
    {
        $descriptions = [
            'sun-moon' => 'Базовая совместимость личностей',
            'venus-mars' => 'Романтическое и сексуальное притяжение',
            'moon-moon' => 'Эмоциональная связь и понимание',
            'mercury-mercury' => 'Коммуникация и интеллектуальная связь',
            'venus-venus' => 'Общие ценности в любви',
            'mars-mars' => 'Энергетическая совместимость',
            'saturn-sun' => 'Стабильность и ответственность',
            'saturn-moon' => 'Эмоциональная безопасность',
            'jupiter-venus' => 'Удача и расширение в любви',
            'north_node-sun' => 'Кармическая связь',
            'north_node-moon' => 'Духовное развитие вместе',
            'chiron-sun' => 'Исцеление через отношения',
        ];

        $key = $p1 . '-' . $p2;
        $keyReverse = $p2 . '-' . $p1;

        return $descriptions[$key] ?? $descriptions[$keyReverse] ?? 'Аспект между планетами';
    }

    /**
     * Calculate 16 category scores based on synastry
     */
    protected function calculateCategoryScores(array $chart1, array $chart2, array $synastry): array
    {
        $baseScore = $synastry['score'];
        $aspects = $synastry['aspects'];

        // Helper to check for aspects between specific planets
        $hasAspect = function($p1, $p2, $nature = null) use ($aspects) {
            foreach ($aspects as $a) {
                if (($a['planet1_key'] === $p1 && $a['planet2_key'] === $p2) ||
                    ($a['planet1_key'] === $p2 && $a['planet2_key'] === $p1)) {
                    if ($nature === null || $a['nature'] === $nature) {
                        return $a;
                    }
                }
            }
            return null;
        };

        $getAspectBonus = function($p1, $p2) use ($hasAspect) {
            $aspect = $hasAspect($p1, $p2);
            if (!$aspect) return 0;
            return $aspect['nature'] === 'harmony' ? 10 : -5;
        };

        // Calculate each category
        $scores = [
            'overall' => $baseScore,

            // Emotional (Moon-Moon, Moon-Venus)
            'emotional' => $this->clampScore($baseScore + $getAspectBonus('moon', 'moon') + $getAspectBonus('moon', 'venus')),

            // Communication (Mercury-Mercury, Mercury-Moon)
            'communication' => $this->clampScore($baseScore + $getAspectBonus('mercury', 'mercury') + $getAspectBonus('mercury', 'moon')),

            // Romantic (Venus-Mars, Venus-Venus)
            'romantic' => $this->clampScore($baseScore + $getAspectBonus('venus', 'mars') + $getAspectBonus('venus', 'venus')),

            // Sexual (Mars-Mars, Venus-Mars, Pluto aspects)
            'sexual' => $this->clampScore($baseScore + $getAspectBonus('mars', 'mars') + $getAspectBonus('venus', 'mars') + $getAspectBonus('mars', 'pluto')),

            // Values (Jupiter-Jupiter, Venus-Saturn)
            'values' => $this->clampScore($baseScore + $getAspectBonus('jupiter', 'jupiter') + $getAspectBonus('venus', 'saturn')),

            // Finance (Saturn-Jupiter, Venus-Saturn)
            'finance' => $this->clampScore($baseScore + $getAspectBonus('saturn', 'jupiter') + $getAspectBonus('venus', 'saturn')),

            // Family (Moon-Saturn, Moon-Jupiter)
            'family' => $this->clampScore($baseScore + $getAspectBonus('moon', 'saturn') + $getAspectBonus('moon', 'jupiter')),

            // Spirituality (Neptune-Sun, Neptune-Moon, North Node)
            'spirituality' => $this->clampScore($baseScore + $getAspectBonus('neptune', 'sun') + $getAspectBonus('north_node', 'sun')),

            // Long-term (Saturn-Sun, Saturn-Moon)
            'longterm' => $this->clampScore($baseScore + $getAspectBonus('saturn', 'sun') + $getAspectBonus('saturn', 'moon')),

            // Conflict resolution (Mercury-Mars, Mars-Saturn)
            'conflict' => $this->clampScore($baseScore + $getAspectBonus('mercury', 'mars') * -1 + $getAspectBonus('mars', 'saturn') * -1),

            // Trust (Saturn aspects, Pluto-Moon)
            'trust' => $this->clampScore($baseScore + $getAspectBonus('saturn', 'venus') + $getAspectBonus('pluto', 'moon') * -1),

            // Support (Jupiter-Moon, Sun-Moon)
            'support' => $this->clampScore($baseScore + $getAspectBonus('jupiter', 'moon') + $getAspectBonus('sun', 'moon')),

            // Adventure (Jupiter-Mars, Uranus aspects)
            'adventure' => $this->clampScore($baseScore + $getAspectBonus('jupiter', 'mars') + $getAspectBonus('uranus', 'mars')),

            // Daily life (Moon-Saturn, Mercury-Mercury)
            'daily' => $this->clampScore($baseScore + $getAspectBonus('moon', 'saturn') + $getAspectBonus('mercury', 'mercury')),

            // Career (Saturn-Sun, Jupiter-Saturn)
            'career' => $this->clampScore($baseScore + $getAspectBonus('saturn', 'sun') + $getAspectBonus('jupiter', 'saturn')),
        ];

        return $scores;
    }

    protected function clampScore(int $score): int
    {
        return max(20, min(98, $score));
    }

    /**
     * Generate AI report for compatibility
     */
    protected function generateAiReport(array $chart1, array $chart2, array $synastry, array $scores): array
    {
        try {
            return $this->aiService->generateCompatibilityReport($chart1, $chart2, $synastry);
        } catch (\Exception $e) {
            Log::error('AI Compatibility Report failed: ' . $e->getMessage());
            return [];
        }
    }
}
