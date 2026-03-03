<?php

namespace App\Services;

class HoroscopeTemplateService
{
    private const RUSSIAN_TO_INDEX = [
        'Овен' => 0, 'Телец' => 1, 'Близнецы' => 2, 'Рак' => 3,
        'Лев' => 4, 'Дева' => 5, 'Весы' => 6, 'Скорпион' => 7,
        'Стрелец' => 8, 'Козерог' => 9, 'Водолей' => 10, 'Рыбы' => 11,
    ];

    private const SIGN_TO_INDEX = [
        'aries' => 0, 'taurus' => 1, 'gemini' => 2, 'cancer' => 3,
        'leo' => 4, 'virgo' => 5, 'libra' => 6, 'scorpio' => 7,
        'sagittarius' => 8, 'capricorn' => 9, 'aquarius' => 10, 'pisces' => 11,
    ];

    /**
     * Generate daily horoscope content using real transit data + template selection.
     * No API calls — fully algorithmic.
     */
    public function generate(array $transitData, string $sign, string $date, string $locale = 'en'): array
    {
        $templates = $this->getTemplates($locale);
        $seed = $this->computeSeed($transitData, $sign, $date);

        // Generate scores based on transit aspects (60-95 range for realism)
        $baseScore = 60 + ($seed % 20);
        $harmonyBonus = $this->countHarmony($transitData);

        return [
            'overview' => $this->pick($templates['overview'], $seed),
            'love' => $this->pick($templates['love'], $seed + 1),
            'career' => $this->pick($templates['career'], $seed + 2),
            'health' => $this->pick($templates['health'], $seed + 3),
            'lucky_number' => ($seed % 99) + 1,
            'lucky_color' => $this->getLuckyColor($seed),
            'mood' => $this->getMood($seed, $locale),
            'scores' => [
                'overall' => min(95, $baseScore + $harmonyBonus),
                'love' => min(95, 55 + (($seed + 1) % 30) + ($harmonyBonus / 2)),
                'career' => min(95, 55 + (($seed + 2) % 30) + ($harmonyBonus / 2)),
                'health' => min(95, 60 + (($seed + 3) % 25) + ($harmonyBonus / 2)),
                'luck' => min(95, 50 + (($seed + 4) % 35) + $harmonyBonus),
            ],
            'element' => $this->getElement($sign),
            'ruling_planet' => $this->getRulingPlanet($sign),
            'compatible_signs' => $this->getCompatibleSigns($sign),
        ];
    }

    private function countHarmony(array $transitData): int
    {
        $harmony = 0;
        foreach ($transitData['aspects'] ?? [] as $a) {
            if (($a['nature'] ?? '') === 'Гармония') {
                $harmony += 3;
            }
        }
        return min(15, $harmony);
    }

    private function getLuckyColor(int $seed): string
    {
        $colors = ['red', 'blue', 'green', 'gold', 'purple', 'silver', 'orange', 'pink', 'white', 'turquoise'];
        return $colors[$seed % count($colors)];
    }

    private function getMood(int $seed, string $locale): string
    {
        $moods = [
            'en' => ['Energetic', 'Reflective', 'Passionate', 'Calm', 'Adventurous', 'Creative', 'Determined', 'Harmonious'],
            'ru' => ['Энергичный', 'Задумчивый', 'Страстный', 'Спокойный', 'Авантюрный', 'Творческий', 'Решительный', 'Гармоничный'],
            'es' => ['Energético', 'Reflexivo', 'Apasionado', 'Tranquilo', 'Aventurero', 'Creativo', 'Decidido', 'Armonioso'],
            'fr' => ['Énergique', 'Réfléchi', 'Passionné', 'Calme', 'Aventureux', 'Créatif', 'Déterminé', 'Harmonieux'],
            'pt' => ['Energético', 'Reflexivo', 'Apaixonado', 'Calmo', 'Aventureiro', 'Criativo', 'Determinado', 'Harmonioso'],
            'hi' => ['ऊर्जावान', 'विचारशील', 'भावुक', 'शांत', 'साहसी', 'रचनात्मक', 'दृढ़', 'सामंजस्यपूर्ण'],
        ];
        $localeMoods = $moods[$locale] ?? $moods['en'];
        return $localeMoods[$seed % count($localeMoods)];
    }

    private function getElement(string $sign): string
    {
        $elements = [
            'aries' => 'fire', 'leo' => 'fire', 'sagittarius' => 'fire',
            'taurus' => 'earth', 'virgo' => 'earth', 'capricorn' => 'earth',
            'gemini' => 'air', 'libra' => 'air', 'aquarius' => 'air',
            'cancer' => 'water', 'scorpio' => 'water', 'pisces' => 'water',
        ];
        return $elements[strtolower($sign)] ?? 'fire';
    }

    private function getRulingPlanet(string $sign): string
    {
        $planets = [
            'aries' => 'mars', 'taurus' => 'venus', 'gemini' => 'mercury',
            'cancer' => 'moon', 'leo' => 'sun', 'virgo' => 'mercury',
            'libra' => 'venus', 'scorpio' => 'pluto', 'sagittarius' => 'jupiter',
            'capricorn' => 'saturn', 'aquarius' => 'uranus', 'pisces' => 'neptune',
        ];
        return $planets[strtolower($sign)] ?? 'sun';
    }

    private function getCompatibleSigns(string $sign): array
    {
        $compatible = [
            'aries' => ['leo', 'sagittarius', 'gemini'],
            'taurus' => ['virgo', 'capricorn', 'cancer'],
            'gemini' => ['libra', 'aquarius', 'aries'],
            'cancer' => ['scorpio', 'pisces', 'taurus'],
            'leo' => ['aries', 'sagittarius', 'libra'],
            'virgo' => ['taurus', 'capricorn', 'cancer'],
            'libra' => ['gemini', 'aquarius', 'leo'],
            'scorpio' => ['cancer', 'pisces', 'virgo'],
            'sagittarius' => ['aries', 'leo', 'aquarius'],
            'capricorn' => ['taurus', 'virgo', 'scorpio'],
            'aquarius' => ['gemini', 'libra', 'sagittarius'],
            'pisces' => ['cancer', 'scorpio', 'capricorn'],
        ];
        return $compatible[strtolower($sign)] ?? ['leo', 'sagittarius', 'gemini'];
    }

    private function computeSeed(array $transitData, string $sign, string $date): int
    {
        $dateNum = (int) str_replace('-', '', $date);
        $signIdx = self::SIGN_TO_INDEX[strtolower($sign)] ?? 0;

        $moonSign = $transitData['moon']['sign'] ?? 'Овен';
        $sunSign = $transitData['sun']['sign'] ?? 'Овен';
        $moonIdx = self::RUSSIAN_TO_INDEX[$moonSign] ?? 0;
        $sunIdx = self::RUSSIAN_TO_INDEX[$sunSign] ?? 0;

        $harmony = 0;
        $tension = 0;
        foreach ($transitData['aspects'] ?? [] as $a) {
            if (($a['nature'] ?? '') === 'Гармония') {
                $harmony++;
            } else {
                $tension++;
            }
        }

        return abs(crc32("{$dateNum}-{$signIdx}-{$moonIdx}-{$sunIdx}-{$harmony}-{$tension}"));
    }

    private function pick(array $pool, int $seed): string
    {
        if (empty($pool)) {
            return '';
        }
        return $pool[$seed % count($pool)];
    }

    private function getTemplates(string $locale): array
    {
        $key = 'horoscope_templates';
        $path = lang_path("{$locale}/{$key}.php");

        if (!file_exists($path)) {
            $path = lang_path("en/{$key}.php");
        }

        if (!file_exists($path)) {
            return $this->getFallbackTemplates($locale);
        }

        $templates = require $path;
        return [
            'overview' => $templates['overview'] ?? [],
            'love' => $templates['love'] ?? [],
            'career' => $templates['career'] ?? [],
            'health' => $templates['health'] ?? [],
        ];
    }

    private function getFallbackTemplates(string $locale): array
    {
        return [
            'overview' => [__('horoscope.fallback_overview', [], $locale)],
            'love' => [__('horoscope.fallback_love', [], $locale)],
            'career' => [__('horoscope.fallback_career', [], $locale)],
            'health' => [__('horoscope.fallback_health', [], $locale)],
        ];
    }
}
