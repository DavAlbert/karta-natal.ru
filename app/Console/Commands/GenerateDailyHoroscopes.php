<?php

namespace App\Console\Commands;

use App\Models\DailyHoroscope;
use App\Services\HoroscopeTemplateService;
use App\Services\DailyTransitService;
use Illuminate\Console\Command;

class GenerateDailyHoroscopes extends Command
{
    protected $signature = 'horoscope:generate {date?} {--locale=} {--sign=}';
    protected $description = 'Generate daily horoscopes for all signs (uses real planetary transits)';

    public function handle(DailyTransitService $transit, HoroscopeTemplateService $templates): int
    {
        $date = $this->argument('date') ?? now()->format('Y-m-d');
        $locales = $this->option('locale')
            ? explode(',', $this->option('locale'))
            : config('app.available_locales', ['en']);
        $signs = $this->option('sign')
            ? explode(',', $this->option('sign'))
            : DailyHoroscope::SIGNS;

        $transitData = $transit->getTransitsForDate($date);
        $this->info("Transits for {$date}: Moon in " . ($transitData['moon']['sign'] ?? '?') . ", Sun in " . ($transitData['sun']['sign'] ?? '?') . "\n");

        $bar = $this->output->createProgressBar(count($signs) * count($locales));
        $bar->start();

        foreach ($signs as $sign) {
            $sign = strtolower($sign);
            if (!DailyHoroscope::isValidSign($sign)) {
                $this->warn("Invalid sign: {$sign}");
                continue;
            }

            foreach ($locales as $locale) {
                $locale = trim($locale);
                $existing = DailyHoroscope::where('date', $date)->where('sign', $sign)->where('locale', $locale)->first();
                if ($existing) {
                    $bar->advance();
                    continue;
                }

                $content = $templates->generate($transitData, $sign, $date, $locale);

                DailyHoroscope::updateOrCreate(
                    ['date' => $date, 'sign' => $sign, 'locale' => $locale],
                    ['transit_data' => $transitData, 'content' => $content]
                );

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Generated horoscopes for {$date}.");

        return Command::SUCCESS;
    }
}
