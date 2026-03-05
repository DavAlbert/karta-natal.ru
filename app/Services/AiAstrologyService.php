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

    public function generateReport(array $chartData, string $locale = 'en', array $personInfo = []): array
    {
        $langInstruction = $this->getLanguageInstruction($locale);
        $langName = $this->getLanguageName($locale);
        $personName = $personInfo['name'] ?? '';
        $personGender = $personInfo['gender'] ?? '';

        $toolSchema = [
            'type' => 'function',
            'function' => [
                'name' => 'generate_astrology_report',
                'description' => trans('astrology.tool_report_desc', [], $locale),
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'identity' => ['type' => 'string', 'description' => trans('astrology.tool_identity_desc', [], $locale) . " In {$langName}. Use markdown: **bold** for key terms, bullet lists for traits. 4-6 paragraphs."],
                        'strengths_weaknesses' => ['type' => 'string', 'description' => trans('astrology.tool_strengths_desc', [], $locale) . " In {$langName}. Use markdown: ### subheadings, **bold**, bullet lists. 3-5 paragraphs."],
                        'love' => ['type' => 'string', 'description' => trans('astrology.tool_love_desc', [], $locale) . " In {$langName}. Use markdown formatting. 4-6 paragraphs."],
                        'career' => ['type' => 'string', 'description' => trans('astrology.tool_career_desc', [], $locale) . " In {$langName}. Use markdown formatting. 4-6 paragraphs."],
                        'health' => ['type' => 'string', 'description' => trans('astrology.tool_health_desc', [], $locale) . " In {$langName}. Use markdown formatting. 3-4 paragraphs."],
                        'karma' => ['type' => 'string', 'description' => trans('astrology.tool_karma_desc', [], $locale) . " In {$langName}. Use markdown formatting. 3-5 paragraphs."],
                        'forecast' => ['type' => 'string', 'description' => trans('astrology.tool_forecast_desc', [], $locale) . " In {$langName}. Use markdown formatting. 3-4 paragraphs."],
                    ],
                    'required' => ['identity', 'strengths_weaknesses', 'love', 'career', 'health', 'karma', 'forecast']
                ]
            ]
        ];

        try {
            Log::info('Starting AI Report Generation', ['locale' => $locale]);

            $context = $this->buildChartContext($chartData, $locale);
            $systemPrompt = trans('astrology.report_system_prompt', [], $locale) . "\n\n" . $langInstruction;

            $personalContext = '';
            if ($personName) {
                $personalContext .= "\n\nClient name: {$personName}.";
            }
            if ($personGender) {
                $personalContext .= " Gender: {$personGender}.";
            }
            $personalContext .= "\n\nIMPORTANT: Address the client by their first name throughout the report. Write as if speaking directly to them (\"you\", \"{$personName}\"). For every claim, explain WHY based on specific planets, signs, houses from their chart. Use simple, warm language. Add a brief summary at the end of each section.";

            $userPrompt = $context . $personalContext . "\n\n" . trans('astrology.report_user_prompt', [], $locale);

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
                    'max_tokens' => 8000,
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
}
