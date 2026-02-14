<?php

namespace App\Jobs;

use App\Mail\ChartReady;
use App\Models\NatalChart;
use App\Services\AiAstrologyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessNatalChartJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 300;
    public string $queue = 'ai-reports';

    public function __construct(
        public NatalChart $chart,
        public bool $sendEmail = false,
        public bool $generateAiReport = true
    ) {
    }

    public function handle(AiAstrologyService $aiService): void
    {
        try {
            // Generate AI report if requested and not already completed
            if ($this->generateAiReport && $this->chart->report_status !== 'completed') {
                $this->chart->update(['report_status' => 'processing']);

                try {
                    $report = $aiService->generateReport($this->chart->chart_data);

                    $this->chart->update([
                        'report_status' => 'completed',
                        'report_content' => $report,
                    ]);
                } catch (\Exception $e) {
                    Log::error('AI Report generation failed: ' . $e->getMessage());
                    $this->chart->update(['report_status' => 'failed']);
                    // Don't throw - we still want to send email
                }
            }

            // Send email if requested
            if ($this->sendEmail) {
                $user = $this->chart->user;
                if ($user && $user->email) {
                    try {
                        Mail::to($user->email)->send(new ChartReady($this->chart));
                    } catch (\Exception $e) {
                        Log::error('Chart ready email failed: ' . $e->getMessage());
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('ProcessNatalChartJob failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
