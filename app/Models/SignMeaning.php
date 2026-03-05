<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignMeaning extends Model
{
    protected $fillable = [
        'sign',
        'locale',
        'characteristics',
        'ruling_planet',
        'element',
        'quality',
        'career',
        'relationships',
    ];

    public $timestamps = false;

    public static function getForSign(string $sign, ?string $locale = null): ?self
    {
        $locale = $locale ?? app()->getLocale();
        return static::where('sign', $sign)->where('locale', $locale)->first()
            ?? static::where('sign', $sign)->where('locale', 'en')->first();
    }
}
