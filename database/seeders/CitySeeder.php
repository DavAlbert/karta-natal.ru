<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Deleting existing cities...');
        City::query()->delete();

        $txtPath = base_path('cities500.txt');

        if (!file_exists($txtPath)) {
            $this->command->error('cities500.txt not found!');
            return;
        }

        $this->command->info('Reading cities from cities500.txt...');

        $lines = file($txtPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->command->info('Found ' . count($lines) . ' cities...');

        $inserted = 0;
        $skipped = 0;
        $existingCities = [];

        foreach ($lines as $line) {
            $parts = explode("\t", $line);

            // GeoNames format:
            // 0: geonameid, 1: name, 2: asciiname, 3: alternatenames, 4: latitude, 5: longitude,
            // 6: feature_class, 7: feature_code, 8: country_code, 9: cc2, 10: admin1,
            // 11: admin2, 12: admin3, 13: admin4, 14: population, 15: elevation, 16: dem, 17: timezone
            if (count($parts) < 18) {
                continue;
            }

            $originalName = $parts[1]; // Original name (may have diacritics)
            $asciiName = $parts[2];    // ASCII name (romanized)
            $alternateNames = $parts[3]; // Comma-separated alternate names
            $latitude = (float) $parts[4];
            $longitude = (float) $parts[5];
            $countryCode = $parts[8];
            $timezone = $parts[17] ?? '';

            if (empty($timezone)) {
                continue;
            }

            $gmtOffset = $this->getGmtOffset($timezone);

            if ($gmtOffset === null) {
                continue;
            }

            // Unique key: name + country
            $cityKey = strtolower($asciiName) . '_' . $countryCode;

            if (in_array($cityKey, $existingCities)) {
                $skipped++;
                continue;
            }

            $existingCities[] = $cityKey;

            // Extract Russian name from alternate names
            $nameRu = $this->extractRussianName($alternateNames, $asciiName);

            // Create normalized name for search (transliterated)
            $nameNormalized = $this->normalizeForSearch($asciiName, $nameRu);

            City::create([
                'name' => $asciiName,
                'name_ru' => $nameRu,
                'alternate_names' => $alternateNames,
                'name_normalized' => $nameNormalized,
                'country' => $countryCode,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'timezone_gmt' => $gmtOffset,
            ]);

            $inserted++;
        }

        $this->command->info("Done! Inserted: {$inserted}, Duplicates skipped: {$skipped}");
    }

    /**
     * Extract Russian (Cyrillic) name from alternate names
     */
    private function extractRussianName(string $alternateNames, string $asciiName): ?string
    {
        if (empty($alternateNames)) {
            return null;
        }

        $names = explode(',', $alternateNames);
        $russianNames = [];
        $otherCyrillicNames = [];

        // Standard Russian alphabet only (А-Яа-яЁё plus common punctuation)
        $russianOnly = '/^[А-Яа-яЁё\s\-\.]+$/u';

        // Non-Russian Cyrillic letters (Ukrainian, Belarusian, Serbian, Kazakh, Chuvash, etc.)
        // ӳ = Chuvash, ү = Kazakh/Tatar, ә = Kazakh, і = Ukrainian/Belarusian, etc.
        $nonRussianLetters = '/[ўђѓєіїјљњћќґҐәөүұқғңһӘӨҮҰҚҒҢҺӣӯӱӳҳҷҹңғқүұəӘІіЎўӸ]/u';

        foreach ($names as $name) {
            $name = trim($name);

            // Skip empty or too short names
            if (mb_strlen($name) < 2) {
                continue;
            }

            // Skip names with administrative suffixes or case endings
            // But keep base city names like "Киев", "Москва" etc.
            if (preg_match('/(город|область|край|район|округ| ош|ом$|ою$|ою$)/ui', $name)) {
                continue;
            }

            // Check if name is purely Cyrillic
            if (preg_match('/^[\p{Cyrillic}\s\-\.]+$/u', $name)) {
                // Check if it uses only Russian letters
                if (preg_match($russianOnly, $name) && !preg_match($nonRussianLetters, $name)) {
                    // Extra check: skip names with ы after consonants at the start (Kazakh pattern like "Кыив")
                    if (!preg_match('/^[КкМмТтБб]ы/u', $name)) {
                        $russianNames[] = $name;
                    }
                } else {
                    $otherCyrillicNames[] = $name;
                }
            }
        }

        // Prefer Russian names, fallback to other Cyrillic
        $candidates = !empty($russianNames) ? $russianNames : $otherCyrillicNames;

        if (empty($candidates)) {
            return null;
        }

        // Prioritize names that look like proper Russian city names
        // 1. Names starting with "Санкт" are special (Sankt-Petersburg)
        foreach ($candidates as $name) {
            if (preg_match('/^Санкт/u', $name)) {
                return $name;
            }
        }

        // 2. Score-based matching - prioritize names that match the ASCII name best
        $asciiLower = strtolower($asciiName);
        $asciiLen = strlen($asciiName);
        $bestMatch = null;
        $bestScore = -1000;

        foreach ($candidates as $name) {
            $len = mb_strlen($name);

            // Skip too long names
            if ($len > 50) {
                continue;
            }

            $score = 0;
            $nameLower = mb_strtolower($name);

            // Compare transliterated Cyrillic to ASCII
            $transliterated = $this->transliterateToLatin($nameLower);

            // Exact match bonus
            if ($transliterated === $asciiLower) {
                $score += 500;
            }

            // Count matching characters from the beginning
            $matchingChars = 0;
            $minLen = min(strlen($transliterated), strlen($asciiLower));
            for ($i = 0; $i < $minLen; $i++) {
                if ($transliterated[$i] === $asciiLower[$i]) {
                    $matchingChars++;
                } else {
                    break;
                }
            }
            $score += $matchingChars * 30; // Heavy weight for prefix matching

            // Prefer names with similar length to ASCII name
            $lenDiff = abs($len - $asciiLen);
            $score -= $lenDiff * 10;

            // Prefer shorter names (more likely to be the main name)
            $score -= $len * 2;

            // Prefer names that are purely standard Russian
            if (preg_match($russianOnly, $name)) {
                $score += 20;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $name;
            }
        }

        return $bestMatch ?: $candidates[0];
    }

    /**
     * Create normalized search string
     */
    private function normalizeForSearch(string $asciiName, ?string $nameRu): string
    {
        $parts = [strtolower($asciiName)];

        if ($nameRu) {
            $parts[] = mb_strtolower($nameRu);
            // Add transliterated version of Russian name
            $parts[] = $this->transliterateToLatin($nameRu);
        }

        return implode(' ', array_unique($parts));
    }

    /**
     * Transliterate Cyrillic to Latin for search
     */
    private function transliterateToLatin(string $text): string
    {
        $map = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch',
            'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        ];

        return strtolower(strtr($text, $map));
    }

    private function getGmtOffset(string $timezone): ?int
    {
        // Try to get offset dynamically
        try {
            $tz = new \DateTimeZone($timezone);
            $now = new \DateTime('now', $tz);
            $offsetSeconds = $tz->getOffset($now);
            return (int) round($offsetSeconds / 3600);
        } catch (\Exception $e) {
            // Fallback to static map for common timezones
            $offsets = [
                'Pacific/Honolulu' => -10,
                'America/Anchorage' => -9,
                'America/Los_Angeles' => -8,
                'America/Phoenix' => -7,
                'America/Denver' => -7,
                'America/Chicago' => -6,
                'America/New_York' => -5,
                'America/Toronto' => -5,
                'America/Halifax' => -4,
                'America/Sao_Paulo' => -3,
                'Atlantic/South_Georgia' => -2,
                'Atlantic/Azores' => -1,
                'Europe/London' => 0,
                'Europe/Dublin' => 0,
                'Europe/Lisbon' => 0,
                'Europe/Paris' => 1,
                'Europe/Berlin' => 1,
                'Europe/Rome' => 1,
                'Europe/Madrid' => 1,
                'Europe/Vienna' => 1,
                'Europe/Zurich' => 1,
                'Europe/Amsterdam' => 1,
                'Europe/Brussels' => 1,
                'Europe/Stockholm' => 1,
                'Europe/Warsaw' => 1,
                'Europe/Prague' => 1,
                'Europe/Budapest' => 1,
                'Europe/Bucharest' => 2,
                'Europe/Athens' => 2,
                'Europe/Helsinki' => 2,
                'Europe/Kiev' => 2,
                'Europe/Minsk' => 3,
                'Europe/Moscow' => 3,
                'Europe/St_Petersburg' => 3,
                'Europe/Volgograd' => 4,
                'Europe/Samara' => 4,
                'Asia/Dubai' => 4,
                'Asia/Tbilisi' => 4,
                'Asia/Yerevan' => 4,
                'Asia/Baku' => 4,
                'Asia/Almaty' => 6,
                'Asia/Dhaka' => 6,
                'Asia/Bangkok' => 7,
                'Asia/Jakarta' => 7,
                'Asia/Shanghai' => 8,
                'Asia/Hong_Kong' => 8,
                'Asia/Taipei' => 8,
                'Asia/Seoul' => 9,
                'Asia/Tokyo' => 9,
                'Australia/Sydney' => 10,
                'Australia/Melbourne' => 10,
                'Australia/Brisbane' => 10,
                'Pacific/Auckland' => 12,
            ];

            return $offsets[$timezone] ?? null;
        }
    }
}
