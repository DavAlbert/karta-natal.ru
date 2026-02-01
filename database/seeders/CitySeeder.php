<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing cities first
        $this->command->info('Deleting existing cities...');
        \App\Models\NatalChart::query()->update(['city_id' => null]);
        \App\Models\City::query()->delete();

        $jsonPath = base_path('cities_coordinates.json');

        if (!file_exists($jsonPath)) {
            $this->command->error('cities_coordinates.json not found!');
            return;
        }

        $cities = json_decode(file_get_contents($jsonPath), true);

        if (!$cities) {
            $this->command->error('Failed to parse cities_coordinates.json');
            return;
        }

        $this->command->info('Importing ' . count($cities) . ' cities...');

        foreach ($cities as $city) {
            // Skip cities with missing required data
            if (empty($city['city_name']) || !isset($city['latitude']) || !isset($city['longitude']) || !isset($city['timezone_gmt'])) {
                $this->command->warn('Skipping city with missing data: ' . ($city['city_name'] ?? 'unknown'));
                continue;
            }

            \App\Models\City::create([
                'name' => $city['city_name'],
                'latitude' => $city['latitude'],
                'longitude' => $city['longitude'],
                'timezone_gmt' => $city['timezone_gmt'],
            ]);
        }

        $this->command->info('Cities imported successfully!');
    }
}
