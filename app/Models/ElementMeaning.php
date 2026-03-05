<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElementMeaning extends Model
{
    protected $fillable = [
        'element',
        'locale',
        'characteristics',
        'strengths',
        'challenges',
        'compatibility',
    ];

    public $timestamps = false;

    public static function getForElement(string $element, ?string $locale = null): ?self
    {
        $locale = $locale ?? app()->getLocale();
        return static::where('element', $element)->where('locale', $locale)->first()
            ?? static::where('element', $element)->where('locale', 'en')->first();
    }
}
