<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyHoroscope extends Model
{
    protected $fillable = ['date', 'sign', 'locale', 'transit_data', 'content'];

    protected $casts = [
        'date' => 'date',
        'transit_data' => 'array',
        'content' => 'array',
    ];

    public const SIGNS = [
        'aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo',
        'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces',
    ];

    public static function isValidSign(string $sign): bool
    {
        return in_array(strtolower($sign), self::SIGNS);
    }
}
