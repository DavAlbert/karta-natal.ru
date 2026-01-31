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

            $response = Http::withToken($this->apiKey)
                ->timeout(120)
                ->retry(2, 5000)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert Vedic and Western Astrologer. You speak strictly Russian. Your goal is to provide deep, mystical, yet practical insights.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $this->buildReportPrompt($chartData)
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

                // Extract arguments from tool call
                $toolCalls = $content['choices'][0]['message']['tool_calls'] ?? [];
                if (!empty($toolCalls)) {
                    $jsonStr = $toolCalls[0]['function']['arguments'];
                    // Use mb_convert_encoding if needed, though usually UTF-8 is standard
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
     * Chat with the Astrologer.
     */
    public function chat(string $question, array $chartData, array $history = []): string
    {
        $messages = [
            ['role' => 'system', 'content' => $this->buildSystemPrompt($chartData)]
        ];

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
                return $response->json('choices.0.message.content') ?? '...';
            }
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
        }

        return "Сейчас звезды молчат. Попробуйте позже.";
    }

    protected function buildReportPrompt(array $chartData): string
    {
        $dataStr = json_encode($chartData, JSON_UNESCAPED_UNICODE);
        return <<<EOT
Analyze this Natal Chart and generate a detailed report in Russian.
Chart Data: $dataStr

Please provide insights for Identity, Love, Career, Karma, and a Forecast. 
Ensure the tone is empowering, deep, and astrological.
EOT;
    }

    protected function buildSystemPrompt(array $chartData): string
    {
        $dataStr = json_encode($chartData, JSON_UNESCAPED_UNICODE);
        return "Ты — мудрый и профессиональный астролог. Твой клиент предоставил карту: $dataStr. Отвечай на вопросы на русском языке, глубоко и по сути.";
    }
}
