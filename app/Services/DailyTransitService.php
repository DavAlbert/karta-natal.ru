<?php

namespace App\Services;

class DailyTransitService
{
    public function __construct(
        protected AstrologyCalculationService $astrology
    ) {}

    /**
     * Get planetary positions for a given date (noon UTC at Greenwich).
     * Used as astronomical basis for daily horoscopes.
     */
    public function getTransitsForDate(string $date): array
    {
        $result = $this->astrology->calculate(
            $date,
            '12:00',
            51.5074,  // London latitude
            0.0,      // Greenwich longitude
            0         // UTC
        );

        return [
            'sun' => $result['sun'] ?? null,
            'moon' => $result['moon'] ?? null,
            'planets' => $result['planets'] ?? [],
            'aspects' => $result['aspects'] ?? [],
        ];
    }
}
