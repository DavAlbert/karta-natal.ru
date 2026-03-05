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

    public function __construct(
        public NatalChart $chart,
        public bool $sendEmail = false,
        public bool $generateAiReport = true
    ) {
        $this->onQueue('ai-reports');
    }

    public function handle(AiAstrologyService $aiService): void
    {
        Log::info('ProcessNatalChartJob started', [
            'chart_id' => $this->chart->id,
            'sendEmail' => $this->sendEmail,
            'generateAiReport' => $this->generateAiReport,
        ]);

        try {
            // Generate AI report if requested and not already completed
            if ($this->generateAiReport && $this->chart->report_status !== 'completed') {
                Log::info('Generating AI report for chart', ['chart_id' => $this->chart->id]);
                $this->chart->update(['report_status' => 'processing']);

                try {
                    $report = $aiService->generateReport(
                        $this->chart->chart_data,
                        $this->chart->locale ?? 'en',
                        ['name' => $this->chart->user?->name ?? '', 'gender' => $this->chart->gender ?? '']
                    );

                    $this->chart->update([
                        'report_status' => 'completed',
                        'report_content' => $report,
                    ]);
                    Log::info('AI report generated successfully', ['chart_id' => $this->chart->id]);
                } catch (\Exception $e) {
                    Log::error('AI Report generation failed: ' . $e->getMessage());
                    $this->chart->update(['report_status' => 'failed']);
                    // Don't throw - we still want to send email
                }
            } else {
                Log::info('Skipping AI report', [
                    'chart_id' => $this->chart->id,
                    'generateAiReport' => $this->generateAiReport,
                    'report_status' => $this->chart->report_status,
                ]);
            }

            // Send email if requested
            Log::info('Email check', [
                'chart_id' => $this->chart->id,
                'sendEmail' => $this->sendEmail,
            ]);

            if ($this->sendEmail) {
                $user = $this->chart->user;
                Log::info('Attempting to send email', [
                    'chart_id' => $this->chart->id,
                    'user_id' => $user?->id,
                    'user_email' => $user?->email,
                ]);

                if ($user && $user->email) {
                    try {
                        Mail::to($user->email)->send(new ChartReady($this->chart));
                        Log::info('Chart ready email sent successfully', [
                            'chart_id' => $this->chart->id,
                            'email' => $user->email,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Chart ready email failed: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('No user or email for chart', ['chart_id' => $this->chart->id]);
                }
            } else {
                Log::info('sendEmail is false, skipping email', ['chart_id' => $this->chart->id]);
            }

            Log::info('ProcessNatalChartJob completed', ['chart_id' => $this->chart->id]);

        } catch (\Exception $e) {
            Log::error('ProcessNatalChartJob failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
