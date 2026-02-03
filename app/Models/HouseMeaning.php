<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseMeaning extends Model
{
    protected $fillable = [
        'house_number',
        'general',
        'keywords',
        'ruling_planet',
        'career_work',
        'money_assets',
        'relationships',
        'health',
        'home_family',
    ];

    public $timestamps = false;

    public static function getForHouse(int $houseNumber): ?self
    {
        return static::where('house_number', $houseNumber)->first();
    }
}
