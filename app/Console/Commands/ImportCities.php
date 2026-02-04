<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCities extends Command
{
    protected $signature = 'cities:import {file? : Path to GeoNames file} {--truncate : Clear existing cities}';
    protected $description = 'Import cities from GeoNames data file';

    public function handle(): int
    {
        $file = $this->argument('file') ?? storage_path('app/cities15000.txt');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            $this->info("Download from: https://download.geonames.org/export/dump/cities15000.zip");
            return Command::FAILURE;
        }

        if ($this->option('truncate')) {
            DB::table('cities')->truncate();
            $this->info('Cleared existing cities.');
        }

        $this->info("Importing cities from: $file");
        $this->info('This may take a few minutes...');

        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->error('Cannot open file');
            return Command::FAILURE;
        }

        $batchSize = 1000;
        $cities = [];
        $count = 0;
        $errors = 0;

        // Skip header if present
        $firstLine = fgets($handle);
        if (strpos($firstLine, 'geonameid') !== false) {
            // Header line - skip it
        } else {
            // Not a header, process this line
            rewind($handle);
        }

        // GeoNames format (tab-separated):
        // 0: geonameid, 1: name, 2: asciiname, 3: alternatenames, 4: latitude, 5: longitude,
        // 6: feature class, 7: feature code, 8: country code, 9: cc2, 10: admin1 code,
        // 11: admin2 code, 12: admin3 code, 13: admin4 code, 14: population, 15: elevation,
        // 16: dem, 17: timezone, 18: modification date

        while (($line = fgets($handle)) !== false) {
            $fields = explode("\t", $line);

            if (count($fields) < 19) {
                $errors++;
                continue;
            }

            $name = trim($fields[1]);
            $country = trim($fields[8]);
            $latitude = (float) trim($fields[4]);
            $longitude = (float) trim($fields[5]);
            $timezone = trim($fields[17]);

            // Parse timezone to GMT offset
            // timezone is like "Europe/Berlin", we need GMT+1, GMT+2 etc.
            $gmtOffset = $this->timezoneToGmtOffset($timezone);

            if ($gmtOffset === null) {
                continue; // Skip if we can't parse timezone
            }

            $cities[] = [
                'name' => $name,
                'country' => $country,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'timezone_gmt' => $gmtOffset,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            if (count($cities) >= $batchSize) {
                DB::table('cities')->insert($cities);
                $cities = [];

                if ($count % 10000 === 0) {
                    $this->info("Imported $count cities...");
                }
            }
        }

        // Insert remaining cities
        if (count($cities) > 0) {
            DB::table('cities')->insert($cities);
        }

        fclose($handle);

        $this->info("Done! Imported $count cities. Errors: $errors");

        return Command::SUCCESS;
    }

    private function timezoneToGmtOffset(string $timezone): ?float
    {
        if (empty($timezone)) {
            return null;
        }

        // Common timezones with their GMT offsets
        $offsets = [
            // UTC
            'UTC' => 0,
            // Europe
            'Europe/London' => 0,
            'Europe/Dublin' => 0,
            'Europe/Paris' => 1,
            'Europe/Berlin' => 1,
            'Europe/Rome' => 1,
            'Europe/Madrid' => 1,
            'Europe/Amsterdam' => 1,
            'Europe/Brussels' => 1,
            'Europe/Vienna' => 1,
            'Europe/Warsaw' => 1,
            'Europe/Prague' => 1,
            'Europe/Budapest' => 1,
            'Europe/Stockholm' => 1,
            'Europe/Oslo' => 1,
            'Europe/Copenhagen' => 1,
            'Europe/Helsinki' => 2,
            'Europe/Athens' => 2,
            'Europe/Istanbul' => 3,
            'Europe/Moscow' => 3,
            'Europe/St_Petersburg' => 3,
            'Europe/Volgograd' => 3,
            'Europe/Samara' => 4,
            // Asia
            'Asia/Dubai' => 4,
            'Asia/Kolkata' => 5.5,
            'Asia/Mumbai' => 5.5,
            'Asia/Bangkok' => 7,
            'Asia/Singapore' => 8,
            'Asia/Shanghai' => 8,
            'Asia/Hong_Kong' => 8,
            'Asia/Taipei' => 8,
            'Asia/Seoul' => 9,
            'Asia/Tokyo' => 9,
            'Asia/Yakutsk' => 9,
            // Americas
            'America/New_York' => -5,
            'America/Chicago' => -6,
            'America/Denver' => -7,
            'America/Los_Angeles' => -8,
            'America/Vancouver' => -8,
            'America/Toronto' => -5,
            'America/Mexico_City' => -6,
            'America/Sao_Paulo' => -3,
            'America/Buenos_Aires' => -3,
            'America/Lima' => -5,
            'America/Bogota' => -5,
            // Australia/Oceania
            'Australia/Sydney' => 11,
            'Australia/Melbourne' => 11,
            'Australia/Perth' => 8,
            'Australia/Brisbane' => 10,
            'Pacific/Auckland' => 13,
            'Pacific/Fiji' => 13,
            // Africa
            'Africa/Cairo' => 2,
            'Africa/Johannesburg' => 2,
            'Africa/Lagos' => 1,
            'Africa/Nairobi' => 3,
            // Additional
            'America/Anchorage' => -9,
            'Asia/Jerusalem' => 2,
            'Asia/Kathmandu' => 5.75,
            'Asia/Riyadh' => 3,
            'Asia/Tehran' => 3.5,
        ];

        // Try to match exact timezone
        if (isset($offsets[$timezone])) {
            return $offsets[$timezone];
        }

        // Try to extract from partial matches
        $parts = explode('/', $timezone);
        if (count($parts) >= 2) {
            $continent = $parts[0];
            $city = $parts[1] ?? '';

            // Check if we have a partial match
            foreach ($offsets as $tz => $offset) {
                if (stripos($tz, $city) !== false || stripos($city, $tz) !== false) {
                    return $offset;
                }
            }
        }

        // Default for common continents
        if (stripos($timezone, 'Europe') !== false) return 1;
        if (stripos($timezone, 'America/New_York') !== false) return -5;
        if (stripos($timezone, 'America/Los_Angeles') !== false) return -8;
        if (stripos($timezone, 'America/Chicago') !== false) return -6;
        if (stripos($timezone, 'Asia/Tokyo') !== false) return 9;
        if (stripos($timezone, 'Asia/Shanghai') !== false) return 8;
        if (stripos($timezone, 'Australia/Sydney') !== false) return 11;

        return null;
    }
}
