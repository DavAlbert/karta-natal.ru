<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanetMeaning extends Model
{
    protected $fillable = [
        'planet',
        'description',
        'strengths',
        'weaknesses',
        'career',
        'relationships',
        'health',
    ];

    public $timestamps = false;

    public static function getForPlanet(string $planet): ?self
    {
        return static::where('planet', $planet)->first();
    }
}
