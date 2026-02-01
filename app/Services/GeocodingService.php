<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    protected $baseUrl = 'https://nominatim.openstreetmap.org';

    /**
     * Geocode a location string to coordinates and timezone.
     *
     * @param string $location City name, e.g., "Berlin, Germany"
     * @return array|null ['lat' => float, 'lon' => float, 'timezone' => string, 'display_name' => string]
     */
    public function geocode(string $location): ?array
    {
        // Cache key based on location
        $cacheKey = 'geocode_' . md5(strtolower(trim($location)));

        // Check cache first (cache for 30 days)
        return Cache::remember($cacheKey, 60 * 60 * 24 * 30, function () use ($location) {
            try {
                // Nominatim requires User-Agent header
                $response = Http::withHeaders([
                    'User-Agent' => 'NatalChartApp/1.0 (astrochart@example.com)'
                ])
                    ->timeout(10)
                    ->get($this->baseUrl . '/search', [
                        'q' => $location,
                        'format' => 'json',
                        'limit' => 1,
                        'addressdetails' => 1,
                    ]);

                if (!$response->successful() || empty($response->json())) {
                    Log::warning('Geocoding failed for location: ' . $location);
                    return null;
                }

                $data = $response->json()[0];

                // Get timezone using a simple approximation based on longitude
                // For production, use a proper timezone API or library
                $timezone = $this->getTimezoneFromCoordinates(
                    (float) $data['lat'],
                    (float) $data['lon']
                );

                return [
                    'lat' => (float) $data['lat'],
                    'lon' => (float) $data['lon'],
                    'timezone' => $timezone,
                    'display_name' => $data['display_name'] ?? $location,
                ];
            } catch (\Exception $e) {
                Log::error('Geocoding exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get timezone from coordinates.
     * This is a simplified approximation. For production, use a proper timezone API.
     *
     * @param float $lat
     * @param float $lon
     * @return string
     */
    protected function getTimezoneFromCoordinates(float $lat, float $lon): string
    {
        // Simple approximation: UTC offset based on longitude
        // 15 degrees = 1 hour
        $offset = round($lon / 15);

        // Common timezone mapping (simplified)
        $timezones = [
            -12 => 'Pacific/Auckland',
            -11 => 'Pacific/Midway',
            -10 => 'Pacific/Honolulu',
            -9 => 'America/Anchorage',
            -8 => 'America/Los_Angeles',
            -7 => 'America/Denver',
            -6 => 'America/Chicago',
            -5 => 'America/New_York',
            -4 => 'America/Halifax',
            -3 => 'America/Sao_Paulo',
            -2 => 'Atlantic/South_Georgia',
            -1 => 'Atlantic/Azores',
            0 => 'Europe/London',
            1 => 'Europe/Paris',
            2 => 'Europe/Athens',
            3 => 'Europe/Moscow',
            4 => 'Asia/Dubai',
            5 => 'Asia/Karachi',
            6 => 'Asia/Dhaka',
            7 => 'Asia/Bangkok',
            8 => 'Asia/Singapore',
            9 => 'Asia/Tokyo',
            10 => 'Australia/Sydney',
            11 => 'Pacific/Noumea',
            12 => 'Pacific/Fiji',
        ];

        return $timezones[$offset] ?? 'UTC';
    }

    /**
     * Autocomplete city names (for frontend suggestions).
     *
     * @param string $query Partial city name
     * @return array List of suggestions with display_name and coordinates
     */
    public function autocomplete(string $query): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'NatalChartApp/1.0 (astrochart@example.com)'
            ])
                ->timeout(5)
                ->get($this->baseUrl . '/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 5,
                    'addressdetails' => 1,
                ]);

            if (!$response->successful()) {
                return [];
            }

            $results = $response->json();
            $suggestions = [];

            foreach ($results as $result) {
                $suggestions[] = [
                    'display_name' => $result['display_name'] ?? '',
                    'lat' => (float) ($result['lat'] ?? 0),
                    'lon' => (float) ($result['lon'] ?? 0),
                ];
            }

            return $suggestions;
        } catch (\Exception $e) {
            Log::error('Autocomplete exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get default coordinates (fallback).
     *
     * @return array
     */
    public function getDefaultCoordinates(): array
    {
        return [
            'lat' => 51.5074, // London
            'lon' => -0.1278,
            'timezone' => 'Europe/London',
            'display_name' => 'London, UK (default)',
        ];
    }
}
