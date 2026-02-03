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
}
