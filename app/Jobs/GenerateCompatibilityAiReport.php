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

    public function __construct(
        public PartnerCompatibility $compatibility,
        public array $userChartData,
        public array $partnerChartData
    ) {
        $this->onQueue('ai-reports');
    }

    public function handle(AiAstrologyService $aiService): void
    {
        try {
            $this->compatibility->update(['ai_report_status' => 'processing']);

            $synastry = $this->compatibility->synastry_data;

            // Get names for personalized report
            $locale = $this->compatibility->user?->natalCharts()?->first()?->locale ?? 'en';
            $userName = $this->compatibility->user->name ?? trans('astrology.partner_1', [], $locale);
            $partnerName = $this->compatibility->partner_name ?? trans('astrology.partner_2', [], $locale);

            $report = $aiService->generateCompatibilityReport(
                $this->userChartData,
                $this->partnerChartData,
                $synastry,
                $userName,
                $partnerName,
                $locale
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
