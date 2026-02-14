<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAstrologyService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
        $this->model = config('services.openai.model', 'gpt-4o');
    }

    /**
     * Build comprehensive context from chart data for AI
     */
    protected function buildChartContext(array $chartData): string
    {
        $planets = $chartData['planets'] ?? [];
        $houses = $chartData['houses'] ?? [];
        $aspects = $chartData['aspects'] ?? [];

        $context = "НАТАЛЬНАЯ КАРТА КЛИЕНТА:\n\n";

        // Planets
        $context .= "ПЛАНЕТЫ:\n";
        $planetNames = [
            'sun' => 'Солнце', 'moon' => 'Луна', 'mercury' => 'Меркурий',
            'venus' => 'Венера', 'mars' => 'Марс', 'jupiter' => 'Юпитер',
            'saturn' => 'Сатурн', 'uranus' => 'Уран', 'neptune' => 'Нептун',
            'pluto' => 'Плутон', 'north_node' => 'Северный узел', 'south_node' => 'Южный узел',
            'chiron' => 'Хирон', 'part_fortune' => 'Часть фортуны'
        ];

        foreach ($planets as $key => $planet) {
            if (!is_array($planet)) continue;
            $name = $planetNames[$key] ?? ucfirst($key);
            $sign = $planet['sign'] ?? 'неизвестно';
            $degree = floor($planet['degree'] ?? 0);
            $house = $planet['house'] ?? '-';
            $retro = isset($planet['retrograde']) && $planet['retrograde'] ? ' (ретроградный)' : '';
            $context .= "- {$name}: {$sign} {$degree}°, {$house} дом{$retro}\n";
        }

        // Houses - cusps
        $context .= "\nДОМА (куспиды):\n";
        for ($i = 1; $i <= 12; $i++) {
            if (isset($houses[$i])) {
                $h = $houses[$i];
                $sign = $h['sign'] ?? 'неизвестно';
                $degree = floor($h['degree'] ?? 0);
                $context .= "- {$i} дом: {$sign} {$degree}°\n";
            }
        }

        // Key points
        $context .= "\nКЛЮЧЕВЫЕ ТОЧКИ:\n";
        if (isset($chartData['ascendant'])) {
            $asc = $chartData['ascendant'];
            $context .= "- ASC: {$asc['sign']} {$asc['degree']}°\n";
        }
        if (isset($chartData['mc'])) {
            $mc = $chartData['mc'];
            $context .= "- MC: {$mc['sign']} {$mc['degree']}°\n";
        }

        // Elements
        $context .= "\nСТИХИИ:\n";
        $elemCount = ['fire' => 0, 'earth' => 0, 'air' => 0, 'water' => 0];
        $elemMap = ['Овен' => 'fire', 'Телец' => 'earth', 'Близнецы' => 'air', 'Рак' => 'water',
                    'Лев' => 'fire', 'Дева' => 'earth', 'Весы' => 'air', 'Скорпион' => 'water',
                    'Стрелец' => 'fire', 'Козерог' => 'earth', 'Водолей' => 'air', 'Рыбы' => 'water'];
        foreach ($planets as $p) {
            if (is_array($p) && isset($p['sign'])) {
                $e = $elemMap[$p['sign']] ?? null;
                if ($e) $elemCount[$e]++;
            }
        }
        foreach ($elemCount as $elem => $count) {
            if ($count > 0) {
                $names = ['fire' => 'Огонь', 'earth' => 'Земля', 'air' => 'Воздух', 'water' => 'Вода'];
                $context .= "- {$names[$elem]}: {$count}\n";
            }
        }

        // Dominant element
        $dominant = array_keys($elemCount, max($elemCount))[0] ?? 'fire';
        $elemNames = ['fire' => 'Огонь', 'earth' => 'Земля', 'air' => 'Воздух', 'water' => 'Вода'];
        $context .= "\nДоминирующая стихия: {$elemNames[$dominant]}\n";

        return $context;
    }

    /**
     * Generate a comprehensive natal chart interpretation in Russian using Strict Structured Outputs.
     */
    public function generateReport(array $chartData): array
    {
        $toolSchema = [
            'type' => 'function',
            'function' => [
                'name' => 'generate_astrology_report',
                'description' => 'Generate a deep psychological and karmic astrology report in Russian based on natal chart data.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'identity' => [
                            'type' => 'string',
                            'description' => 'Deep analysis of personality, ego, and emotional nature (Sun/Moon/Ascendant) in Russian.'
                        ],
                        'love' => [
                            'type' => 'string',
                            'description' => 'Analysis of relationships, love language, and needs (Venus/Mars/7th House) in Russian.'
                        ],
                        'career' => [
                            'type' => 'string',
                            'description' => 'Analysis of professional path, ambition, and success (10th House/Saturn/MC) in Russian.'
                        ],
                        'karma' => [
                            'type' => 'string',
                            'description' => 'Analysis of karmic destiny, lessons, and growth (Nodes/Saturn/Retrogrades) in Russian.'
                        ],
                        'forecast' => [
                            'type' => 'string',
                            'description' => 'A brief forecast for the upcoming major transits or phases impacting the user in Russian.'
                        ]
                    ],
                    'required' => ['identity', 'love', 'career', 'karma', 'forecast']
                ]
            ]
        ];

        try {
            Log::info('Starting AI Report Generation', ['chart_data' => array_keys($chartData)]);

            $context = $this->buildChartContext($chartData);

            $response = Http::withToken($this->apiKey)
                ->timeout(120)
                ->retry(2, 5000)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Ты — эксперт Ведической и Западной астрологии. Твоя задача — давать глубокие, мистические, но практичные ответы на основе натальной карты клиента. Говори на русском языке. Всегда ссылайся на конкретные планеты, знаки и дома из карты клиента.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $context . "\n\nСгенерируй подробный астрологический отчёт на русском языке, включая:\n1. Анализ личности (Солнце, Луна, Асцендент)\n2. Анализ любви и отношений (Венера, Марс, 7 дом)\n3. Анализ карьеры (10 дом, Сатурн, МС)\n4. Кармический анализ (Узлы, Сатурн, ретроградные планеты)\n5. Прогноз на ближайший период"
                        ]
                    ],
                    'tools' => [$toolSchema],
                    'tool_choice' => [
                        'type' => 'function',
                        'function' => ['name' => 'generate_astrology_report']
                    ],
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

    /**
     * Chat with the Astrologer using chart context.
     */
    public function chat(string $question, array $chartData, array $history = []): string
    {
        $context = $this->buildChartContext($chartData);

        $messages = [
            [
                'role' => 'system',
                'content' => "Ты — мудрый и профессиональный астролог, эксперт в Ведической и Западной астрологии. Твой клиент предоставил свою натальную карту:\n\n{$context}\n\nТвои правила:\n1. Всегда ссылайся на конкретные планеты, знаки и дома из карты клиента\n2. Давай практичные советы на основе астрологии\n3. Говори на русском языке\n4. Если нужно уточнение — спрашивай\n5. Не выдумывай данные, которых нет в карте"
            ]
        ];

        foreach ($history as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        // Add template context if question is from template
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
                return $response->json('choices.0.message.content') ?? 'Извините, сейчас не могу ответить. Попробуйте позже.';
            }
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
        }

        return "Извините, сейчас звезды молчат. Попробуйте позже.";
    }

    /**
     * Get chat response with streaming (for real-time feel)
     */
    public function chatStream(string $question, array $chartData, array $history = []): string
    {
        // For streaming, we use the same method but client handles the stream
        // This is a placeholder - actual streaming requires SSE or similar
        return $this->chat($question, $chartData, $history);
    }

    /**
     * Generate comprehensive compatibility report with structured outputs.
     * Includes scores (1-10), detailed analysis, and specific recommendations.
     */
    public function generateCompatibilityReport(array $chart1, array $chart2, array $synastry, string $name1 = 'Партнёр 1', string $name2 = 'Партнёр 2'): array
    {
        $toolSchema = [
            'type' => 'function',
            'function' => [
                'name' => 'generate_compatibility_report',
                'description' => 'Generate detailed compatibility analysis between two natal charts in Russian.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'full_description' => [
                            'type' => 'string',
                            'description' => 'Comprehensive 800-1200 word PERSONAL analysis in Russian. USE BOTH PARTNER NAMES! ALWAYS reference specific planets, signs and aspects from their natal charts as proof. Structure: 1) Greet couple by names, describe their core energies (Sun signs, Moon signs, Ascendants - name them!), 2) Explain WHY they attract each other - cite specific aspects (e.g. "Venus in Aries trine Mars in Leo creates..."), 3) Daily life - morning routines, evenings, weekends based on their Moon signs, 4) Communication style based on Mercury placements, 5) Challenges - name the difficult aspects and give CONCRETE solutions (e.g. "When Saturn square Venus triggers jealousy, try..."), 6) Long-term advice for marriage/family. Use simple but professional language. Every claim must reference a planet or aspect.'
                        ],
                        'overall_score' => [
                            'type' => 'integer',
                            'description' => 'Overall compatibility score from 1 to 10 based on synastry analysis.'
                        ],
                        'overall_analysis' => [
                            'type' => 'string',
                            'description' => 'Detailed overall compatibility analysis in Russian.'
                        ],
                        'emotional_score' => [
                            'type' => 'integer',
                            'description' => 'Emotional compatibility score from 1 to 10 (Moon/Moon aspects).'
                        ],
                        'emotional_analysis' => [
                            'type' => 'string',
                            'description' => 'Analysis of emotional compatibility and needs.'
                        ],
                        'communication_score' => [
                            'type' => 'integer',
                            'description' => 'Communication compatibility score from 1 to 10 (Mercury/Mercury aspects).'
                        ],
                        'communication_analysis' => [
                            'type' => 'string',
                            'description' => 'Analysis of how well partners communicate.'
                        ],
                        'romantic_score' => [
                            'type' => 'integer',
                            'description' => 'Romantic compatibility score from 1 to 10 (Venus/Mars/Venus aspects).'
                        ],
                        'romantic_analysis' => [
                            'type' => 'string',
                            'description' => 'Analysis of romantic and sexual chemistry.'
                        ],
                        'values_score' => [
                            'type' => 'integer',
                            'description' => 'Value alignment score from 1 to 10 (Jupiter/Saturn aspects).'
                        ],
                        'values_analysis' => [
                            'type' => 'string',
                            'description' => 'Analysis of shared values and life goals.'
                        ],
                        'growth_score' => [
                            'type' => 'integer',
                            'description' => 'Mutual growth potential score from 1 to 10 (Nodes/Chiron aspects).'
                        ],
                        'growth_analysis' => [
                            'type' => 'string',
                            'description' => 'How partners can help each other grow.'
                        ],
                        'strengths' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'description' => 'List of key strengths of this compatibility (3-5 items).'
                        ],
                        'challenges' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'description' => 'List of main challenges to work through (3-5 items).'
                        ],
                        'recommendations' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'description' => 'Practical recommendations for the relationship (3-5 items).'
                        ]
                    ],
                    'required' => [
                        'full_description',
                        'overall_score', 'overall_analysis',
                        'emotional_score', 'emotional_analysis',
                        'communication_score', 'communication_analysis',
                        'romantic_score', 'romantic_analysis',
                        'values_score', 'values_analysis',
                        'growth_score', 'growth_analysis',
                        'strengths', 'challenges', 'recommendations'
                    ]
                ]
            ]
        ];

        try {
            Log::info('Starting AI Compatibility Report Generation');

            $context1 = $this->buildChartContext($chart1);
            $context2 = $this->buildChartContext($chart2);

            // Build synastry context
            $synastryContext = "СИНАСТРИЯ (аспекты между картами):\n";
            $synastryContext .= "Общий балл совместимости: {$synastry['score']}/100\n";
            $synastryContext .= "Гармоничные аспекты: {$synastry['harmony']}\n";
            $synastryContext .= "Напряжённые аспекты: {$synastry['tension']}\n\n";

            foreach (($synastry['aspects'] ?? []) as $aspect) {
                $nature = $aspect['nature'] === 'harmony' ? '⚡ Гармония' : '⚠ Напряжение';
                $synastryContext .= "- {$aspect['planet1']} → {$aspect['planet2']}: {$aspect['type']} ({$nature})\n";
            }

            $fullContext = "ИМЕНА ПАРТНЁРОВ:\n";
            $fullContext .= "- Первый партнёр: {$name1}\n";
            $fullContext .= "- Второй партнёр: {$name2}\n\n";
            $fullContext .= "НАТАЛЬНАЯ КАРТА {$name1}:\n{$context1}\n\n";
            $fullContext .= "НАТАЛЬНАЯ КАРТА {$name2}:\n{$context2}\n\n";
            $fullContext .= $synastryContext;

            $response = Http::withToken($this->apiKey)
                ->timeout(180)
                ->retry(3, 5000)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Ты — профессиональный астролог-консультант. ПРАВИЛА:\n1. ВСЕГДА называй конкретные планеты и знаки из карт (например: "Солнце Давида в Козероге даёт ему...")\n2. ВСЕГДА объясняй ПОЧЕМУ что-то происходит на основе аспектов (например: "Трин между вашими Лунами означает, что вы интуитивно понимаете эмоции друг друга")\n3. При проблемах — давай КОНКРЕТНЫЕ решения (например: "Когда квадрат Марс-Сатурн вызывает раздражение, попробуйте...")\n4. Пиши простым языком, но профессионально\n5. Обращайся к партнёрам по именам\n6. Описывай реальные жизненные ситуации на основе их карт'
                        ],
                        [
                            'role' => 'user',
                            'content' => $fullContext . "\n\nНапиши ПЕРСОНАЛЬНЫЙ астрологический анализ совместимости {$name1} и {$name2}.\n\nПОЛНОЕ ОПИСАНИЕ (full_description) — 800-1200 слов:\n\n1. ВСТУПЛЕНИЕ: Поприветствуй {$name1} и {$name2}. Кратко опиши их солнечные знаки и общую энергетику пары.\n\n2. ПОЧЕМУ ВЫ ВМЕСТЕ: Объясни притяжение через конкретные аспекты. Например: \"{$name1}, твоя Венера в [знак] образует трин с Марсом {$name2} — это создаёт сильное притяжение и страсть.\"\n\n3. ЭМОЦИОНАЛЬНАЯ СВЯЗЬ: Опиши взаимодействие Лун. Как вы понимаете эмоции друг друга? Приведи примеры из жизни.\n\n4. ОБЩЕНИЕ: Проанализируй Меркурии. Как вы общаетесь? Что помогает, что мешает?\n\n5. СОВМЕСТНЫЙ БЫТ: На основе Лун и 4-х домов опиши, как будет выглядеть ваш дом, утро, вечера.\n\n6. СЛОЖНОСТИ И РЕШЕНИЯ: Назови 2-3 напряжённых аспекта. Для КАЖДОГО дай конкретный совет. Например: \"Квадрат Сатурн-Венера может вызывать холодность. Решение: {$name1}, старайся чаще выражать чувства словами, {$name2} это нужно.\"\n\n7. БУДУЩЕЕ: Долгосрочный прогноз — брак, дети, как вы будете расти вместе.\n\nСИЛЬНЫЕ СТОРОНЫ (strengths): 3-5 пунктов с указанием аспектов\nВЫЗОВЫ (challenges): 3-5 пунктов с указанием аспектов\nРЕКОМЕНДАЦИИ (recommendations): 3-5 конкретных советов для {$name1} и {$name2}'"
                        ]
                    ],
                    'tools' => [$toolSchema],
                    'tool_choice' => [
                        'type' => 'function',
                        'function' => ['name' => 'generate_compatibility_report']
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $content = $response->json();
                $toolCalls = $content['choices'][0]['message']['tool_calls'] ?? [];

                if (!empty($toolCalls)) {
                    $jsonStr = $toolCalls[0]['function']['arguments'];
                    $data = json_decode($jsonStr, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Add synastry data to report
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
