<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityMeaning extends Model
{
    protected $fillable = [
        'quality',
        'locale',
        'characteristics',
        'strengths',
        'challenges',
    ];

    public $timestamps = false;

    public static function getForQuality(string $quality, ?string $locale = null): ?self
    {
        $locale = $locale ?? app()->getLocale();
        return static::where('quality', $quality)->where('locale', $locale)->first()
            ?? static::where('quality', $quality)->where('locale', 'en')->first();
    }
}
