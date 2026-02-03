<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElementMeaning extends Model
{
    protected $fillable = [
        'element',
        'characteristics',
        'strengths',
        'challenges',
        'compatibility',
    ];

    public $timestamps = false;

    public static function getForElement(string $element): ?self
    {
        return static::where('element', $element)->first();
    }
}
