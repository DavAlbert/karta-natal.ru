<?php

namespace App\Jobs;

use App\Models\PartnerCompatibility;
use App\Services\AiAstrologyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateCompatibilityAiReport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 300;
    public string $queue = 'ai-reports';

    public function __construct(
        public PartnerCompatibility $compatibility,
        public array $userChartData,
        public array $partnerChartData
    ) {
    }

    public function handle(AiAstrologyService $aiService): void
    {
        try {
            $this->compatibility->update(['ai_report_status' => 'processing']);

            $synastry = $this->compatibility->synastry_data;

            // Get names for personalized report
            $userName = $this->compatibility->user->name ?? 'Партнёр 1';
            $partnerName = $this->compatibility->partner_name ?? 'Партнёр 2';

            $report = $aiService->generateCompatibilityReport(
                $this->userChartData,
                $this->partnerChartData,
                $synastry,
                $userName,
                $partnerName
            );

            $this->compatibility->update([
                'ai_report' => $report,
                'ai_report_status' => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error('GenerateCompatibilityAiReport failed: ' . $e->getMessage());
            $this->compatibility->update(['ai_report_status' => 'failed']);
            throw $e;
        }
    }
}
