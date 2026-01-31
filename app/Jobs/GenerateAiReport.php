<?php

namespace App\Jobs;

use App\Models\NatalChart;
use App\Services\AiAstrologyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateAiReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public NatalChart $chart
    ) {
    }

    public function handle(AiAstrologyService $aiService): void
    {
        $this->chart->update(['report_status' => 'processing']);

        try {
            $report = $aiService->generateReport($this->chart->chart_data);

            $this->chart->update([
                'report_status' => 'completed',
                'report_content' => $report,
            ]);
        } catch (\Exception $e) {
            $this->chart->update(['report_status' => 'failed']);
            throw $e;
        }
    }
}
