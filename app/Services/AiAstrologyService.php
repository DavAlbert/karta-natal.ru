<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAstrologyService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    protected const LANGUAGE_MAP = [
        'en' => 'English',
        'fr' => 'French',
        'es' => 'Spanish',
        'pt' => 'Portuguese',
        'hi' => 'Hindi',
        'ru' => 'Russian',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
        $this->model = config('services.openai.model', 'gpt-4o');
    }

    protected function getLanguageName(string $locale): string
    {
        return self::LANGUAGE_MAP[$locale] ?? 'English';
    }

    protected function getLanguageInstruction(string $locale): string
    {
        return trans('astrology.respond_in_language', ['language' => $this->getLanguageName($locale)], $locale);
    }

    protected function buildChartContext(array $chartData, string $locale = 'en'): string
    {
        $planets = $chartData['planets'] ?? [];
        $houses = $chartData['houses'] ?? [];

        $planetNames = [
            'sun' => trans('astrology.planet_sun', [], $locale),
            'moon' => trans('astrology.planet_moon', [], $locale),
            'mercury' => trans('astrology.planet_mercury', [], $locale),
            'venus' => trans('astrology.planet_venus', [], $locale),
            'mars' => trans('astrology.planet_mars', [], $locale),
            'jupiter' => trans('astrology.planet_jupiter', [], $locale),
            'saturn' => trans('astrology.planet_saturn', [], $locale),
            'uranus' => trans('astrology.planet_uranus', [], $locale),
            'neptune' => trans('astrology.planet_neptune', [], $locale),
            'pluto' => trans('astrology.planet_pluto', [], $locale),
            'north_node' => trans('astrology.planet_north_node', [], $locale),
            'south_node' => trans('astrology.planet_south_node', [], $locale),
            'chiron' => trans('astrology.planet_chiron', [], $locale),
            'part_fortune' => trans('astrology.planet_part_fortune', [], $locale),
        ];

        $signMap = $this->getSignTranslationMap($locale);
        $houseLabel = trans('astrology.ctx_house', [], $locale);
        $retroLabel = trans('astrology.ctx_retrograde', [], $locale);
        $unknownLabel = trans('astrology.ctx_unknown', [], $locale);

        $context = trans('astrology.ctx_natal_chart', [], $locale) . "\n\n";
        $context .= trans('astrology.ctx_planets', [], $locale) . "\n";

        foreach ($planets as $key => $planet) {
            if (!is_array($planet)) continue;
            $name = $planetNames[$key] ?? ucfirst($key);
            $sign = $signMap[$planet['sign'] ?? ''] ?? ($planet['sign'] ?? $unknownLabel);
            $degree = floor($planet['degree'] ?? 0);
            $house = $planet['house'] ?? '-';
            $retro = isset($planet['retrograde']) && $planet['retrograde'] ? ' ' . $retroLabel : '';
            $context .= "- {$name}: {$sign} {$degree}°, {$house} {$houseLabel}{$retro}\n";
        }

        $context .= "\n" . trans('astrology.ctx_houses', [], $locale) . "\n";
        for ($i = 1; $i <= 12; $i++) {
            if (isset($houses[$i])) {
                $h = $houses[$i];
                $sign = $signMap[$h['sign'] ?? ''] ?? ($h['sign'] ?? $unknownLabel);
                $degree = floor($h['degree'] ?? 0);
                $context .= "- {$i} {$houseLabel}: {$sign} {$degree}°\n";
            }
        }

        $context .= "\n" . trans('astrology.ctx_key_points', [], $locale) . "\n";
        if (isset($chartData['ascendant'])) {
            $asc = $chartData['ascendant'];
            $sign = $signMap[$asc['sign'] ?? ''] ?? ($asc['sign'] ?? '');
            $context .= "- ASC: {$sign} {$asc['degree']}°\n";
        }
        if (isset($chartData['mc'])) {
            $mc = $chartData['mc'];
            $sign = $signMap[$mc['sign'] ?? ''] ?? ($mc['sign'] ?? '');
            $context .= "- MC: {$sign} {$mc['degree']}°\n";
        }

        $elemNames = [
            'fire' => trans('astrology.element_fire', [], $locale),
            'earth' => trans('astrology.element_earth', [], $locale),
            'air' => trans('astrology.element_air', [], $locale),
            'water' => trans('astrology.element_water', [], $locale),
        ];

        $elemCount = ['fire' => 0, 'earth' => 0, 'air' => 0, 'water' => 0];
        $signToElem = $this->getSignElementMap();

        foreach ($planets as $p) {
            if (is_array($p) && isset($p['sign'])) {
                $e = $signToElem[$p['sign']] ?? null;
                if ($e) $elemCount[$e]++;
            }
        }

        $context .= "\n" . trans('astrology.ctx_elements', [], $locale) . "\n";
        foreach ($elemCount as $elem => $count) {
            if ($count > 0) {
                $context .= "- {$elemNames[$elem]}: {$count}\n";
            }
        }

        $dominant = array_keys($elemCount, max($elemCount))[0] ?? 'fire';
        $context .= "\n" . trans('astrology.ctx_dominant_element', [], $locale) . " {$elemNames[$dominant]}\n";

        return $context;
    }

    /**
     * Map from Russian sign names (stored in chart_data) to localized sign names.
     */
    protected function getSignTranslationMap(string $locale): array
    {
        $russianToKey = [
            'Овен' => 'aries', 'Телец' => 'taurus', 'Близнецы' => 'gemini',
            'Рак' => 'cancer', 'Лев' => 'leo', 'Дева' => 'virgo',
            'Весы' => 'libra', 'Скорпион' => 'scorpio', 'Стрелец' => 'sagittarius',
            'Козерог' => 'capricorn', 'Водолей' => 'aquarius', 'Рыбы' => 'pisces',
        ];

        $map = [];
        foreach ($russianToKey as $russian => $key) {
            $map[$russian] = trans('astrology.sign_' . $key, [], $locale);
        }
        return $map;
    }

    protected function getSignElementMap(): array
    {
        return [
            'Овен' => 'fire', 'Телец' => 'earth', 'Близнецы' => 'air', 'Рак' => 'water',
            'Лев' => 'fire', 'Дева' => 'earth', 'Весы' => 'air', 'Скорпион' => 'water',
            'Стрелец' => 'fire', 'Козерог' => 'earth', 'Водолей' => 'air', 'Рыбы' => 'water',
        ];
    }

    public function generateReport(array $chartData, string $locale = 'en'): array
    {
        $langInstruction = $this->getLanguageInstruction($locale);
        $langName = $this->getLanguageName($locale);

        $toolSchema = [
            'type' => 'function',
            'function' => [
                'name' => 'generate_astrology_report',
                'description' => trans('astrology.tool_report_desc', [], $locale),
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'identity' => ['type' => 'string', 'description' => trans('astrology.tool_identity_desc', [], $locale) . " In {$langName}."],
                        'love' => ['type' => 'string', 'description' => trans('astrology.tool_love_desc', [], $locale) . " In {$langName}."],
                        'career' => ['type' => 'string', 'description' => trans('astrology.tool_career_desc', [], $locale) . " In {$langName}."],
                        'karma' => ['type' => 'string', 'description' => trans('astrology.tool_karma_desc', [], $locale) . " In {$langName}."],
                        'forecast' => ['type' => 'string', 'description' => trans('astrology.tool_forecast_desc', [], $locale) . " In {$langName}."],
                    ],
                    'required' => ['identity', 'love', 'career', 'karma', 'forecast']
                ]
            ]
        ];

        try {
            Log::info('Starting AI Report Generation', ['locale' => $locale]);

            $context = $this->buildChartContext($chartData, $locale);
            $systemPrompt = trans('astrology.report_system_prompt', [], $locale) . "\n\n" . $langInstruction;
            $userPrompt = $context . "\n\n" . trans('astrology.report_user_prompt', [], $locale);

            $response = Http::withToken($this->apiKey)
                ->timeout(120)
                ->retry(2, 5000)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'tools' => [$toolSchema],
                    'tool_choice' => ['type' => 'function', 'function' => ['name' => 'generate_astrology_report']],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $content = $response->json();
                $toolCalls = $content['choices'][0]['message']['tool_calls'] ?? [];
                if (!empty($toolCalls)) {
                    $jsonStr = $toolCalls[0]['function']['arguments'];
                    $structuredData = json_decode($jsonStr, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $structuredData;
                    }
                    Log::error('AI JSON Decode Error: ' . json_last_error_msg());
                }
            } else {
                Log::error('AI API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('AI Critical Exception: ' . $e->getMessage());
        }

        return [];
    }

    public function chat(string $question, array $chartData, array $history = [], string $locale = 'en'): string
    {
        $context = $this->buildChartContext($chartData, $locale);
        $langInstruction = $this->getLanguageInstruction($locale);

        $systemContent = str_replace(':context', $context, trans('astrology.chat_system_prompt', [], $locale));
        $systemContent .= "\n\n" . $langInstruction;

        $messages = [['role' => 'system', 'content' => $systemContent]];

        foreach ($history as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? trans('astrology.chat_error', [], $locale);
            }
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
        }

        return trans('astrology.chat_error_stars', [], $locale);
    }

    public function chatStream(string $question, array $chartData, array $history = [], string $locale = 'en'): string
    {
        return $this->chat($question, $chartData, $history, $locale);
    }

    public function generateCompatibilityReport(array $chart1, array $chart2, array $synastry, string $name1 = '', string $name2 = '', string $locale = 'en'): array
    {
        $name1 = $name1 ?: trans('astrology.partner_1', [], $locale);
        $name2 = $name2 ?: trans('astrology.partner_2', [], $locale);
        $langInstruction = $this->getLanguageInstruction($locale);
        $langName = $this->getLanguageName($locale);

        $toolSchema = [
            'type' => 'function',
            'function' => [
                'name' => 'generate_compatibility_report',
                'description' => "Generate detailed compatibility analysis between two natal charts in {$langName}.",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'full_description' => ['type' => 'string', 'description' => "Comprehensive 800-1200 word PERSONAL analysis in {$langName}. USE BOTH PARTNER NAMES! Reference specific planets, signs and aspects."],
                        'overall_score' => ['type' => 'integer', 'description' => 'Overall compatibility score from 1 to 10.'],
                        'overall_analysis' => ['type' => 'string', 'description' => "Detailed overall compatibility analysis in {$langName}."],
                        'emotional_score' => ['type' => 'integer', 'description' => 'Emotional compatibility score 1-10.'],
                        'emotional_analysis' => ['type' => 'string', 'description' => "Emotional compatibility analysis in {$langName}."],
                        'communication_score' => ['type' => 'integer', 'description' => 'Communication compatibility score 1-10.'],
                        'communication_analysis' => ['type' => 'string', 'description' => "Communication analysis in {$langName}."],
                        'romantic_score' => ['type' => 'integer', 'description' => 'Romantic compatibility score 1-10.'],
                        'romantic_analysis' => ['type' => 'string', 'description' => "Romantic chemistry analysis in {$langName}."],
                        'values_score' => ['type' => 'integer', 'description' => 'Value alignment score 1-10.'],
                        'values_analysis' => ['type' => 'string', 'description' => "Values analysis in {$langName}."],
                        'growth_score' => ['type' => 'integer', 'description' => 'Mutual growth score 1-10.'],
                        'growth_analysis' => ['type' => 'string', 'description' => "Growth potential analysis in {$langName}."],
                        'strengths' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => "3-5 key strengths in {$langName}."],
                        'challenges' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => "3-5 challenges in {$langName}."],
                        'recommendations' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => "3-5 recommendations in {$langName}."],
                    ],
                    'required' => [
                        'full_description', 'overall_score', 'overall_analysis',
                        'emotional_score', 'emotional_analysis', 'communication_score', 'communication_analysis',
                        'romantic_score', 'romantic_analysis', 'values_score', 'values_analysis',
                        'growth_score', 'growth_analysis', 'strengths', 'challenges', 'recommendations'
                    ]
                ]
            ]
        ];

        try {
            Log::info('Starting AI Compatibility Report Generation', ['locale' => $locale]);

            $context1 = $this->buildChartContext($chart1, $locale);
            $context2 = $this->buildChartContext($chart2, $locale);

            $harmonyLabel = trans('astrology.synastry_harmony_nature', [], $locale);
            $tensionLabel = trans('astrology.synastry_tension_nature', [], $locale);

            $synastryContext = trans('astrology.synastry_label', [], $locale) . "\n";
            $synastryContext .= trans('astrology.synastry_score', [], $locale) . " {$synastry['score']}/100\n";
            $synastryContext .= trans('astrology.synastry_harmony', [], $locale) . " {$synastry['harmony']}\n";
            $synastryContext .= trans('astrology.synastry_tension', [], $locale) . " {$synastry['tension']}\n\n";

            foreach (($synastry['aspects'] ?? []) as $aspect) {
                $nature = $aspect['nature'] === 'harmony' ? $harmonyLabel : $tensionLabel;
                $synastryContext .= "- {$aspect['planet1']} → {$aspect['planet2']}: {$aspect['type']} ({$nature})\n";
            }

            $fullContext = "PARTNER NAMES:\n- {$name1}\n- {$name2}\n\n";
            $fullContext .= "{$name1}'s NATAL CHART:\n{$context1}\n\n";
            $fullContext .= "{$name2}'s NATAL CHART:\n{$context2}\n\n";
            $fullContext .= $synastryContext;

            $systemPrompt = trans('astrology.compatibility_system_prompt', [], $locale) . "\n\n" . $langInstruction;

            $response = Http::withToken($this->apiKey)
                ->timeout(180)
                ->retry(3, 5000)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $fullContext . "\n\nWrite a PERSONAL astrological compatibility analysis of {$name1} and {$name2} in {$langName}."],
                    ],
                    'tools' => [$toolSchema],
                    'tool_choice' => ['type' => 'function', 'function' => ['name' => 'generate_compatibility_report']],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $content = $response->json();
                $toolCalls = $content['choices'][0]['message']['tool_calls'] ?? [];
                if (!empty($toolCalls)) {
                    $jsonStr = $toolCalls[0]['function']['arguments'];
                    $data = json_decode($jsonStr, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $data['synastry'] = $synastry;
                        return $data;
                    }
                    Log::error('Compatibility AI JSON Decode Error: ' . json_last_error_msg());
                }
            } else {
                Log::error('Compatibility AI API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Compatibility AI Exception: ' . $e->getMessage());
        }

        return [];
    }
}
