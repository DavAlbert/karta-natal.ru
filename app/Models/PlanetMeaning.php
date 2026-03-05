<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanetMeaning extends Model
{
    protected $fillable = [
        'planet',
        'locale',
        'description',
        'strengths',
        'weaknesses',
        'career',
        'relationships',
        'health',
    ];

    public $timestamps = false;

    public static function getForPlanet(string $planet, ?string $locale = null): ?self
    {
        $locale = $locale ?? app()->getLocale();
        return static::where('planet', $planet)->where('locale', $locale)->first()
            ?? static::where('planet', $planet)->where('locale', 'en')->first();
    }
}
