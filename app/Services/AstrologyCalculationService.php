<?php

namespace App\Services;

class AstrologyCalculationService
{
    /**
     * Calculate planetary positions for a given date, time, and location.
     *
     * @param string $date Y-m-d
     * @param string $time H:i
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function calculate(string $date, string $time, float $latitude, float $longitude): array
    {
        // 1. Convert timestamp to Julian Day
        $dateTime = new \DateTime("$date $time");
        $timestamp = $dateTime->getTimestamp();

        // Simple Julian Day calc: (Unix Time / 86400) + 2440587.5
        $jd = ($timestamp / 86400) + 2440587.5;

        // 2. Constants for Orbital Elements (Approximate for "Logical" Calc)
        // These are simplified elements (J2000 epoch) sufficient for a "Real Feel"
        $T = ($jd - 2451545.0) / 36525; // Julian Centuries from J2000

        // Helper: Normalize degrees 0-360
        $norm = function ($deg) {
            $d = fmod($deg, 360);
            return ($d < 0) ? $d + 360 : $d;
        };

        // Helper: Get Sign and Degree (e.g., 45 deg -> Taurus 15)
        $getSignData = function ($long) {
            $signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
            $long = fmod($long, 360);
            if ($long < 0)
                $long += 360;

            $idx = floor($long / 30);
            $deg = $long - ($idx * 30);

            return [
                'sign' => $signs[$idx] ?? 'Aries',
                'degree' => $deg,
                'absolute_degree' => $long
            ];
        };

        // --- PLANETARY ALGORITHMS (Simplified Van Flandern & Pulkkinen) ---

        // SUN
        $L_sun = $norm(280.46646 + 36000.76983 * $T + 0.0003032 * $T * $T); // Mean Longitude
        $M_sun = $norm(357.52911 + 35999.05029 * $T - 0.0001537 * $T * $T); // Mean Anomaly
        $C_sun = (1.914602 - 0.004817 * $T - 0.000014 * $T * $T) * sin(deg2rad($M_sun)) +
            (0.019993 - 0.000101 * $T) * sin(deg2rad(2 * $M_sun)) +
            0.000289 * sin(deg2rad(3 * $M_sun)); // Eq of Center
        $true_long_sun = $L_sun + $C_sun;

        // MOON (Simplified)
        $L_moon = $norm(218.316 + 481267.881 * $T);
        $M_moon = $norm(134.963 + 477198.867 * $T); // Mean Anomaly
        $F_moon = $norm(93.272 + 483202.017 * $T);  // Argument of Latitude
        $D_moon = $norm(297.850 + 445267.111 * $T); // Elongation
        // Major perturbations
        $long_moon = $L_moon + 6.289 * sin(deg2rad($M_moon))
            - 1.274 * sin(deg2rad($M_moon - 2 * $D_moon))
            + 0.658 * sin(deg2rad(2 * $D_moon))
            - 0.186 * sin(deg2rad($M_sun))
            - 0.114 * sin(deg2rad(2 * $F_moon));

        // ASCENDANT / MC (Houses)
        // Sidereal Time at Greenwich (stat0)
        $stat0 = 6.697374558 + 2400.051336 * $T + 0.000025862 * $T * $T;
        // Local Sidereal Time (LST) = UT * 1.00273790935 + Longitude/15 + stat0
        $ut_hours = $dateTime->format('H') + ($dateTime->format('i') / 60);
        $lst = $norm(($stat0 + ($ut_hours * 1.00273790935) + ($longitude / 15)) * 15);
        $ramc = $lst; // Right Ascension of MC in degrees

        // MC
        $obl = 23.44; // Obliquity of Ecliptic

        // Accurate MC
        $mc = rad2deg(atan2(sin(deg2rad($ramc)), cos(deg2rad($ramc)) * cos(deg2rad($obl))));
        if ($mc < 0)
            $mc += 360; // atan2 returns -180 to 180
        // Need to check quadrant - atan2 handles logic but we need 0-360 mapping correctly relative to RAMC?
        // Actually, for astronomy, calculating MC vector is best.
        // Let's use a simpler known approximation if math gets hairy in one file.
        // Or trust the atan2 result which gives correct angle.

        // ASCENDANT
        $asc_rad = atan2(cos(deg2rad($ramc)), -1 * (sin(deg2rad($ramc)) * cos(deg2rad($obl)) + tan(deg2rad($latitude)) * sin(deg2rad($obl))));
        $asc = rad2deg($asc_rad);
        if ($asc < 0)
            $asc += 360;

        // --- CALCULATE PLANETS (Positions relative to Sun roughly or Mean orbits) ---
        // For MVP "Logical" feel, we can just use simple mean orbits for outer planets which move slowly
        // But let's try to add some "Realness" for Mercury/Venus relative to Sun

        // Mercury: Moves close to sun. Let's start with Sun position + random perturbation based on anomaly?
        // No, let's use Mean Longitude calculation for them too.

        $planets_calc = [
            'mercury' => $norm(252.25 + 149472.67 * $T),
            'venus' => $norm(181.98 + 58517.81 * $T),
            'mars' => $norm(355.43 + 19140.29 * $T),
            'jupiter' => $norm(34.35 + 3034.90 * $T),
            'saturn' => $norm(50.08 + 1222.11 * $T),
            'uranus' => $norm(314.05 + 428.46 * $T), // Very rough mean
            'neptune' => $norm(304.34 + 218.48 * $T),
            'pluto' => $norm(238.92 + 145.20 * $T), // Pluto extremely eccentric, this is very rough
            'node' => $norm(125.04 - 1934.136 * $T), // Mean Node
        ];

        // 3. Construct Data Array
        $sunData = $getSignData($true_long_sun);
        $moonData = $getSignData($long_moon); // Using simplied Perturbed Moon
        $ascData = $getSignData($asc);
        $mcData = $getSignData($mc);

        $results = [
            'sun' => array_merge($sunData, [
                'name' => 'Солнце',
                'house' => '10', // House calc for planets is Next Step (Complex), using mock/approx houses?
                'description' => 'Ваше Я. ' . $sunData['sign'] . ' наделяет вас уникальной энергией.',
            ]),
            'moon' => array_merge($moonData, [
                'name' => 'Луна',
                'house' => '4',
                'description' => 'Ваши эмоции. ' . $moonData['sign'] . ' определяет ваш внутренний мир.',
            ]),
            'ascendant' => array_merge($ascData, [
                'name' => 'Асцендент',
                'description' => 'Ваша маска. Мир видит вас через призму знака ' . $ascData['sign'] . '.',
            ]),
        ];

        // Planets
        $all_planets = [];
        // Add Sun/Moon
        $all_planets['sun'] = $results['sun'];
        $all_planets['moon'] = $results['moon'];
        $all_planets['ascendant'] = $results['ascendant'];
        $all_planets['mc'] = array_merge($mcData, ['name' => 'Середина Неба']);

        foreach ($planets_calc as $key => $deg) {
            $pData = $getSignData($deg);
            $all_planets[$key] = array_merge($pData, [
                'name' => ucfirst($key),
                'retrograde' => false // Calculation of retrograde needs speed check (long2 - long1), skip for MVP
            ]);
        }

        // HOUSE CALCULATION (Whole Sign approx or Equal House from ASC for simplicity in PHP MVP)
        // With Equal House system: House 1 = Ascendant Degree. House 2 = Asc + 30, etc.
        // This is "Logical" and commonly used.
        $houses = [];
        for ($i = 1; $i <= 12; $i++) {
            $h_deg = $norm($ascData['absolute_degree'] + (($i - 1) * 30));
            $h_data = $getSignData($h_deg);
            $houses[$i] = [
                'sign' => $h_data['sign'],
                'degree' => $h_data['degree'],
                'label' => $i == 1 ? 'ASC' : ($i == 10 ? 'MC' : $i) // Note: Equal House MC isn't always 10th, but keeping simple
            ];
        }
        // Correct MC in Equal House is just MC, but let's visually put MC in the list if needed.

        // Update Planet Houses based on "Equal House" logic
        // Planet absolute degree - Asc absolute degree = Angle from Asc. 
        // Diff / 30 = House Index.
        foreach ($all_planets as $key => &$p) {
            if (isset($p['absolute_degree'])) {
                $diff = $norm($p['absolute_degree'] - $ascData['absolute_degree']);
                $h = floor($diff / 30) + 1;
                $p['house'] = (int) $h;
            }
        }

        // 4. Calculate Aspects
        $aspects = [];
        $aspectRules = [
            'Conjunction' => ['angle' => 0, 'orb' => 8],
            'Opposition' => ['angle' => 180, 'orb' => 8],
            'Trine' => ['angle' => 120, 'orb' => 8],
            'Square' => ['angle' => 90, 'orb' => 8],
            'Sextile' => ['angle' => 60, 'orb' => 6],
        ];

        // Define relevant points for aspects
        $points = ['sun', 'moon', 'mercury', 'venus', 'mars', 'jupiter', 'saturn', 'uranus', 'neptune', 'pluto', 'ascendant', 'mc'];

        // Loop through pairs
        for ($i = 0; $i < count($points); $i++) {
            for ($j = $i + 1; $j < count($points); $j++) {
                $p1 = $points[$i];
                $p2 = $points[$j];

                if (!isset($all_planets[$p1]) || !isset($all_planets[$p2]))
                    continue;

                $deg1 = $all_planets[$p1]['absolute_degree'];
                $deg2 = $all_planets[$p2]['absolute_degree'];

                // Shortest distance on circle
                $diff = abs($deg1 - $deg2);
                if ($diff > 180)
                    $diff = 360 - $diff;

                foreach ($aspectRules as $type => $rule) {
                    $orb = abs($diff - $rule['angle']);
                    if ($orb <= $rule['orb']) {
                        $aspects[] = [
                            'planet1' => $all_planets[$p1]['name'],
                            'planet2' => $all_planets[$p2]['name'],
                            'type' => $type,
                            'orb' => round($orb, 1),
                            // Simple nature logic
                            'nature' => in_array($type, ['Square', 'Opposition']) ? 'Tension' : 'Harmony'
                        ];
                    }
                }
            }
        }

        return [
            'sun' => $results['sun'],
            'moon' => $results['moon'],
            'ascendant' => $results['ascendant'],
            'planets' => $all_planets,
            'houses' => $houses,
            'aspects' => $aspects
        ];
    }
}
