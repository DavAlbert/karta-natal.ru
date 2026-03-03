<?php

namespace App\Console\Commands;

use App\Models\DailyHoroscope;
use App\Services\HoroscopeTemplateService;
use App\Services\DailyTransitService;
use Illuminate\Console\Command;

class GenerateDailyHoroscopes extends Command
{
    protected $signature = 'horoscope:generate {--locale=} {--sign=}';
    protected $description = 'Generate daily horoscopes for all signs (uses real planetary transits)';

    public function handle(DailyTransitService $transit, HoroscopeTemplateService $templates): int
    {
        $today = now()->format('Y-m-d');
        $locales = $this->option('locale')
            ? explode(',', $this->option('locale'))
            : config('app.available_locales', ['en']);
        $signs = $this->option('sign')
            ? explode(',', $this->option('sign'))
            : DailyHoroscope::SIGNS;

        $transitData = $transit->getTransitsForDate($today);
        $this->info("Transits for {$today}: Moon in " . ($transitData['moon']['sign'] ?? '?') . ", Sun in " . ($transitData['sun']['sign'] ?? '?') . "\n");

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
                $content = $templates->generate($transitData, $sign, $today, $locale);

                DailyHoroscope::updateOrCreate(
                    ['sign' => $sign, 'locale' => $locale],
                    ['transit_data' => $transitData, 'content' => $content]
                );

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Generated horoscopes for today ({$today}).");

        return Command::SUCCESS;
    }
}
