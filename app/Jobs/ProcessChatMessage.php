<?php

namespace App\Jobs;

use App\Models\ChatMessage;
use App\Models\NatalChart;
use App\Services\AiAstrologyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessChatMessage implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 120;

    public function __construct(
        public ChatMessage $chatMessage,
        public NatalChart $chart,
        public string $userMessage
    ) {
        $this->onQueue('chat');
    }

    public function handle(AiAstrologyService $aiService): void
    {
        try {
            $this->chatMessage->update(['status' => 'processing']);

            $response = $aiService->chat($this->userMessage, $this->chart->chart_data);

            $this->chatMessage->update([
                'content' => $response,
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error('ProcessChatMessage failed: ' . $e->getMessage());
            $this->chatMessage->update([
                'content' => 'Извините, произошла ошибка. Попробуйте ещё раз.',
                'status' => 'failed',
            ]);
        }
    }
}
