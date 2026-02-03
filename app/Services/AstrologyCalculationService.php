<?php

namespace App\Services;

class AstrologyCalculationService
{
    /**
     * Calculate planetary positions for a given date, time, and location.
     *
     * @param string $date Y-m-d
     * @param string $time H:i (local time)
     * @param float $latitude
     * @param float $longitude
     * @param float $timezoneOffset GMT offset in hours (e.g., 3 for GMT+3)
     * @return array
     */
    public function calculate(string $date, string $time, float $latitude, float $longitude, float $timezoneOffset = 0): array
    {
        // 1. Convert LOCAL time to UTC
        $localDateTime = new \DateTime("$date $time");

        // Subtract timezone offset to get UTC
        // e.g., if local is 14:00 GMT+3, UTC is 11:00
        $utcDateTime = clone $localDateTime;
        $offsetMinutes = (int)($timezoneOffset * 60);
        if ($offsetMinutes >= 0) {
            $utcDateTime->sub(new \DateInterval('PT' . abs($offsetMinutes) . 'M'));
        } else {
            $utcDateTime->add(new \DateInterval('PT' . abs($offsetMinutes) . 'M'));
        }

        // 2. Convert UTC timestamp to Julian Day
        $timestamp = $utcDateTime->getTimestamp();
        $jd = ($timestamp / 86400) + 2440587.5;

        // Julian Centuries from J2000.0 (Jan 1, 2000, 12:00 TT)
        $T = ($jd - 2451545.0) / 36525;

        // Helper: Normalize degrees 0-360
        $norm = function ($deg) {
            $d = fmod($deg, 360);
            return ($d < 0) ? $d + 360 : $d;
        };

        // Helper: Get Sign and Degree
        $signs = ['Овен', 'Телец', 'Близнецы', 'Рак', 'Лев', 'Дева', 'Весы', 'Скорпион', 'Стрелец', 'Козерог', 'Водолей', 'Рыбы'];

        $getSignData = function ($long) use ($signs, $norm) {
            $long = $norm($long);
            $idx = (int)floor($long / 30);
            $deg = $long - ($idx * 30);

            return [
                'sign' => $signs[$idx] ?? 'Овен',
                'degree' => $deg,
                'absolute_degree' => $long
            ];
        };

        // Obliquity of Ecliptic (accurate formula)
        $obl = 23.439291 - 0.0130042 * $T - 0.00000016 * $T * $T + 0.000000504 * $T * $T * $T;

        // --- SUN (VSOP87-simplified) ---
        $L_sun = $norm(280.46646 + 36000.76983 * $T + 0.0003032 * $T * $T);
        $M_sun = $norm(357.52911 + 35999.05029 * $T - 0.0001537 * $T * $T);
        $M_sun_rad = deg2rad($M_sun);
        $C_sun = (1.914602 - 0.004817 * $T - 0.000014 * $T * $T) * sin($M_sun_rad)
               + (0.019993 - 0.000101 * $T) * sin(2 * $M_sun_rad)
               + 0.000289 * sin(3 * $M_sun_rad);
        $true_long_sun = $norm($L_sun + $C_sun);

        // --- MOON (Brown's lunar theory simplified) ---
        $L_moon = $norm(218.3165 + 481267.8813 * $T);
        $M_moon = $norm(134.9634 + 477198.8675 * $T);
        $F_moon = $norm(93.2721 + 483202.0175 * $T);
        $D_moon = $norm(297.8502 + 445267.1115 * $T);
        $Om_moon = $norm(125.0446 - 1934.1363 * $T);

        $M_moon_rad = deg2rad($M_moon);
        $F_moon_rad = deg2rad($F_moon);
        $D_moon_rad = deg2rad($D_moon);
        $Om_moon_rad = deg2rad($Om_moon);

        $long_moon = $L_moon
            + 6.289 * sin($M_moon_rad)
            - 1.274 * sin(2 * $D_moon_rad - $M_moon_rad)
            + 0.658 * sin(2 * $D_moon_rad)
            - 0.214 * sin(2 * $M_moon_rad)
            - 0.186 * sin($M_sun_rad)
            - 0.114 * sin(2 * $F_moon_rad)
            + 0.059 * sin(2 * $D_moon_rad - 2 * $M_moon_rad)
            + 0.057 * sin(2 * $D_moon_rad - $M_sun_rad - $M_moon_rad);
        $long_moon = $norm($long_moon);

        // --- SIDEREAL TIME & HOUSES ---
        // Greenwich Mean Sidereal Time at 0h UT
        $GMST0 = 280.46061837 + 360.98564736629 * ($jd - 2451545.0) + 0.000387933 * $T * $T;
        $GMST0 = $norm($GMST0);

        // Local Sidereal Time (using longitude)
        $LST = $norm($GMST0 + $longitude);
        $RAMC = $LST; // Right Ascension of MC in degrees

        // --- MC (Midheaven) ---
        $mc = rad2deg(atan2(sin(deg2rad($RAMC)), cos(deg2rad($RAMC)) * cos(deg2rad($obl))));
        $mc = $norm($mc);

        // Quadrant correction for MC
        if ($RAMC >= 0 && $RAMC < 180) {
            if ($mc < 0 || $mc > 180) $mc = $norm($mc + 180);
        } else {
            if ($mc >= 0 && $mc < 180) $mc = $norm($mc + 180);
        }

        // --- ASCENDANT ---
        $lat_rad = deg2rad($latitude);
        $obl_rad = deg2rad($obl);
        $RAMC_rad = deg2rad($RAMC);

        $y = cos($RAMC_rad);
        $x = -sin($RAMC_rad) * cos($obl_rad) - tan($lat_rad) * sin($obl_rad);
        $asc = rad2deg(atan2($y, $x));
        $asc = $norm($asc);

        // --- PLANETS (Mean elements with first-order corrections) ---
        $planets_longitudes = [
            'mercury' => $this->calcMercury($T, $norm),
            'venus' => $this->calcVenus($T, $norm),
            'mars' => $this->calcMars($T, $norm),
            'jupiter' => $this->calcJupiter($T, $norm),
            'saturn' => $this->calcSaturn($T, $norm),
            'uranus' => $norm(314.055 + 429.8640 * $T),
            'neptune' => $norm(304.349 + 219.8833 * $T),
            'pluto' => $norm(238.958 + 145.4730 * $T),
        ];

        // --- LUNAR NODES ---
        $north_node = $norm(125.0446 - 1934.1363 * $T + 0.0021 * $T * $T);
        $south_node = $norm($north_node + 180);

        // --- CHIRON (simplified) ---
        $chiron = $norm(209.25 + 1.0295 * 36525 * $T / 50.42); // ~50.42 year orbit

        // --- PART OF FORTUNE ---
        // Day formula: ASC + Moon - Sun
        // Night formula: ASC + Sun - Moon (if Sun below horizon)
        $sunAboveHorizon = true; // Simplified - always use day formula
        if ($sunAboveHorizon) {
            $part_fortune = $norm($asc + $long_moon - $true_long_sun);
        } else {
            $part_fortune = $norm($asc + $true_long_sun - $long_moon);
        }

        // --- VERTEX ---
        // Vertex = ASC calculated for co-latitude (90° - lat) with RAMC + 90°
        $coLat = 90 - abs($latitude);
        $coLat_rad = deg2rad($coLat);
        $RAMC_vertex = $norm($RAMC + 90);
        $RAMC_vertex_rad = deg2rad($RAMC_vertex);

        $y_v = cos($RAMC_vertex_rad);
        $x_v = -sin($RAMC_vertex_rad) * cos($obl_rad) - tan($coLat_rad) * sin($obl_rad);
        $vertex = rad2deg(atan2($y_v, $x_v));
        $vertex = $norm($vertex);

        // Build results
        $sunData = $getSignData($true_long_sun);
        $moonData = $getSignData($long_moon);
        $ascData = $getSignData($asc);
        $mcData = $getSignData($mc);

        $planetNames = [
            'sun' => 'Солнце',
            'moon' => 'Луна',
            'mercury' => 'Меркурий',
            'venus' => 'Венера',
            'mars' => 'Марс',
            'jupiter' => 'Юпитер',
            'saturn' => 'Сатурн',
            'uranus' => 'Уран',
            'neptune' => 'Нептун',
            'pluto' => 'Плутон',
            'north_node' => 'Сев. узел',
            'south_node' => 'Южн. узел',
            'chiron' => 'Хирон',
            'part_fortune' => 'Колесо фортуны',
            'vertex' => 'Вертекс',
        ];

        // Build all_planets array
        $all_planets = [];

        $all_planets['sun'] = array_merge($sunData, [
            'name' => 'Солнце',
            'retrograde' => false,
        ]);

        $all_planets['moon'] = array_merge($moonData, [
            'name' => 'Луна',
            'retrograde' => false,
        ]);

        foreach ($planets_longitudes as $key => $deg) {
            $pData = $getSignData($deg);
            $all_planets[$key] = array_merge($pData, [
                'name' => $planetNames[$key] ?? ucfirst($key),
                'retrograde' => false, // TODO: Calculate actual retrograde
            ]);
        }

        // Add nodes and special points
        $all_planets['north_node'] = array_merge($getSignData($north_node), [
            'name' => 'Сев. узел',
            'retrograde' => true, // Nodes are always retrograde in mean motion
        ]);

        $all_planets['south_node'] = array_merge($getSignData($south_node), [
            'name' => 'Южн. узел',
            'retrograde' => true,
        ]);

        $all_planets['chiron'] = array_merge($getSignData($chiron), [
            'name' => 'Хирон',
            'retrograde' => false,
        ]);

        $all_planets['part_fortune'] = array_merge($getSignData($part_fortune), [
            'name' => 'Колесо фортуны',
            'retrograde' => false,
        ]);

        $all_planets['vertex'] = array_merge($getSignData($vertex), [
            'name' => 'Вертекс',
            'retrograde' => false,
        ]);

        // --- HOUSES (Placidus) ---
        $houses = $this->calcPlacidusHouses($asc, $mc, $latitude, $obl, $getSignData, $norm);

        // Assign houses to planets
        foreach ($all_planets as $key => &$p) {
            if (isset($p['absolute_degree'])) {
                $p['house'] = $this->getHouseForDegree($p['absolute_degree'], $houses, $norm);
            }
        }

        // --- ASPECTS ---
        $aspects = $this->calculateAspects($all_planets, $ascData, $mcData);

        return [
            'sun' => $all_planets['sun'],
            'moon' => $all_planets['moon'],
            'ascendant' => array_merge($ascData, ['name' => 'Асцендент']),
            'mc' => array_merge($mcData, ['name' => 'Середина Неба']),
            'planets' => $all_planets,
            'houses' => $houses,
            'aspects' => $aspects,
        ];
    }

    /**
     * Mercury with perturbations
     */
    private function calcMercury(float $T, callable $norm): float
    {
        $L = 252.2509 + 149472.6747 * $T;
        $M = 174.7948 + 149472.5153 * $T;
        $M_rad = deg2rad($M);

        // Equation of center
        $C = 23.4400 * sin($M_rad) + 2.9818 * sin(2 * $M_rad) + 0.5255 * sin(3 * $M_rad);

        return $norm($L + $C / 60); // Convert arcminutes to degrees
    }

    /**
     * Venus with perturbations
     */
    private function calcVenus(float $T, callable $norm): float
    {
        $L = 181.9798 + 58517.8157 * $T;
        $M = 50.4161 + 58517.8039 * $T;
        $M_rad = deg2rad($M);

        $C = 0.7758 * sin($M_rad) + 0.0033 * sin(2 * $M_rad);

        return $norm($L + $C);
    }

    /**
     * Mars with perturbations
     */
    private function calcMars(float $T, callable $norm): float
    {
        $L = 355.4330 + 19140.2993 * $T;
        $M = 19.3730 + 19139.8585 * $T;
        $M_rad = deg2rad($M);

        $C = 10.6912 * sin($M_rad) + 0.6228 * sin(2 * $M_rad) + 0.0503 * sin(3 * $M_rad);

        return $norm($L + $C / 60);
    }

    /**
     * Jupiter with perturbations
     */
    private function calcJupiter(float $T, callable $norm): float
    {
        $L = 34.3515 + 3034.9057 * $T;
        $M = 20.0202 + 3034.6962 * $T;
        $M_rad = deg2rad($M);

        $C = 5.5549 * sin($M_rad) + 0.1683 * sin(2 * $M_rad);

        return $norm($L + $C / 60);
    }

    /**
     * Saturn with perturbations
     */
    private function calcSaturn(float $T, callable $norm): float
    {
        $L = 50.0774 + 1222.1138 * $T;
        $M = 317.0207 + 1222.1116 * $T;
        $M_rad = deg2rad($M);

        $C = 6.3585 * sin($M_rad) + 0.2204 * sin(2 * $M_rad);

        return $norm($L + $C / 60);
    }

    /**
     * Calculate Placidus houses
     */
    private function calcPlacidusHouses(float $asc, float $mc, float $latitude, float $obl, callable $getSignData, callable $norm): array
    {
        $houses = [];

        // Houses 1, 4, 7, 10 are fixed
        $houses[1] = $getSignData($asc);
        $houses[4] = $getSignData($norm($mc + 180)); // IC
        $houses[7] = $getSignData($norm($asc + 180)); // Descendant
        $houses[10] = $getSignData($mc);

        // For intermediate houses, use semi-arc method (simplified Placidus)
        // This is a reasonable approximation
        $lat_rad = deg2rad($latitude);
        $obl_rad = deg2rad($obl);

        // Calculate house cusps 2, 3, 5, 6, 8, 9, 11, 12
        // Using equal division as fallback for extreme latitudes
        if (abs($latitude) > 66) {
            // Near polar - use equal houses
            for ($i = 2; $i <= 12; $i++) {
                if (!isset($houses[$i])) {
                    $houses[$i] = $getSignData($norm($asc + (($i - 1) * 30)));
                }
            }
        } else {
            // Placidus approximation
            $diff_asc_mc = $norm($asc - $mc);
            if ($diff_asc_mc > 180) $diff_asc_mc = 360 - $diff_asc_mc;

            // Houses 11, 12 (between MC and ASC)
            $houses[11] = $getSignData($norm($mc + $diff_asc_mc / 3));
            $houses[12] = $getSignData($norm($mc + 2 * $diff_asc_mc / 3));

            // Houses 2, 3 (between ASC and IC)
            $diff_asc_ic = $norm($norm($mc + 180) - $asc);
            if ($diff_asc_ic > 180) $diff_asc_ic = 360 - $diff_asc_ic;
            $houses[2] = $getSignData($norm($asc + $diff_asc_ic / 3));
            $houses[3] = $getSignData($norm($asc + 2 * $diff_asc_ic / 3));

            // Opposite houses
            $houses[5] = $getSignData($norm($houses[11]['absolute_degree'] + 180));
            $houses[6] = $getSignData($norm($houses[12]['absolute_degree'] + 180));
            $houses[8] = $getSignData($norm($houses[2]['absolute_degree'] + 180));
            $houses[9] = $getSignData($norm($houses[3]['absolute_degree'] + 180));
        }

        // Sort by house number
        ksort($houses);

        return $houses;
    }

    /**
     * Determine which house a degree falls in
     */
    private function getHouseForDegree(float $degree, array $houses, callable $norm): int
    {
        $degree = $norm($degree);

        for ($h = 1; $h <= 12; $h++) {
            $nextH = $h == 12 ? 1 : $h + 1;

            $start = $houses[$h]['absolute_degree'];
            $end = $houses[$nextH]['absolute_degree'];

            // Handle wrap-around
            if ($end < $start) {
                if ($degree >= $start || $degree < $end) {
                    return $h;
                }
            } else {
                if ($degree >= $start && $degree < $end) {
                    return $h;
                }
            }
        }

        return 1; // Fallback
    }

    /**
     * Calculate aspects between planets
     */
    private function calculateAspects(array $planets, array $ascData, array $mcData): array
    {
        $aspects = [];
        $aspectRules = [
            'Соединение' => ['angle' => 0, 'orb' => 8],
            'Оппозиция' => ['angle' => 180, 'orb' => 8],
            'Трин' => ['angle' => 120, 'orb' => 8],
            'Квадрат' => ['angle' => 90, 'orb' => 7],
            'Секстиль' => ['angle' => 60, 'orb' => 6],
        ];

        // Points to consider for aspects
        $points = array_merge($planets, [
            'ascendant' => array_merge($ascData, ['name' => 'Асцендент']),
            'mc' => array_merge($mcData, ['name' => 'MC']),
        ]);

        // Remove non-aspect points
        unset($points['part_fortune'], $points['vertex']);

        $keys = array_keys($points);
        $count = count($keys);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $p1 = $points[$keys[$i]];
                $p2 = $points[$keys[$j]];

                if (!isset($p1['absolute_degree']) || !isset($p2['absolute_degree'])) continue;

                $deg1 = $p1['absolute_degree'];
                $deg2 = $p2['absolute_degree'];

                $diff = abs($deg1 - $deg2);
                if ($diff > 180) $diff = 360 - $diff;

                foreach ($aspectRules as $type => $rule) {
                    $orb = abs($diff - $rule['angle']);
                    if ($orb <= $rule['orb']) {
                        $aspects[] = [
                            'planet1' => $p1['name'],
                            'planet2' => $p2['name'],
                            'type' => $type,
                            'orb' => round($orb, 1),
                            'nature' => in_array($type, ['Квадрат', 'Оппозиция']) ? 'Напряжение' : 'Гармония'
                        ];
                        break; // Only one aspect per pair
                    }
                }
            }
        }

        // Sort by orb (tighter aspects first)
        usort($aspects, fn($a, $b) => $a['orb'] <=> $b['orb']);

        return $aspects;
    }
}
