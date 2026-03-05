<?php

namespace Database\Seeders;

use App\Models\PlanetMeaning;
use App\Models\SignMeaning;
use App\Models\HouseMeaning;
use App\Models\AspectMeaning;
use App\Models\ElementMeaning;
use App\Models\QualityMeaning;
use Illuminate\Database\Seeder;

class AstrologyMeaningsSeeder extends Seeder
{
    public function run(): void
    {
        $locales = config('app.available_locales', ['en']);

        foreach ($locales as $locale) {
            $dataFile = __DIR__ . "/data/{$locale}.php";

            if (!file_exists($dataFile)) {
                $this->command?->warn("No data file for locale: {$locale}, skipping.");
                continue;
            }

            $data = require $dataFile;
            $this->command?->info("Seeding astrology meanings for locale: {$locale}");

            $this->seedPlanets($data['planets'], $locale);
            $this->seedSigns($data['signs'], $locale);
            $this->seedHouses($data['houses'], $locale);
            $this->seedAspects($data['aspects'], $locale);
            $this->seedElements($data['elements'], $locale);
            $this->seedQualities($data['qualities'], $locale);
        }
    }

    private function seedPlanets(array $planets, string $locale): void
    {
        foreach ($planets as $planet => $data) {
            PlanetMeaning::updateOrCreate(
                ['planet' => $planet, 'locale' => $locale],
                $data
            );
        }
    }

    private function seedSigns(array $signs, string $locale): void
    {
        foreach ($signs as $sign => $data) {
            SignMeaning::updateOrCreate(
                ['sign' => $sign, 'locale' => $locale],
                $data
            );
        }
    }

    private function seedHouses(array $houses, string $locale): void
    {
        foreach ($houses as $num => $data) {
            HouseMeaning::updateOrCreate(
                ['house_number' => $num, 'locale' => $locale],
                $data
            );
        }
    }

    private function seedAspects(array $aspects, string $locale): void
    {
        foreach ($aspects as $type => $data) {
            AspectMeaning::updateOrCreate(
                ['aspect_type' => $type, 'locale' => $locale],
                $data
            );
        }
    }

    private function seedElements(array $elements, string $locale): void
    {
        foreach ($elements as $element => $data) {
            ElementMeaning::updateOrCreate(
                ['element' => $element, 'locale' => $locale],
                $data
            );
        }
    }

    private function seedQualities(array $qualities, string $locale): void
    {
        foreach ($qualities as $quality => $data) {
            QualityMeaning::updateOrCreate(
                ['quality' => $quality, 'locale' => $locale],
                $data
            );
        }
    }
}
