<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignMeaning extends Model
{
    protected $fillable = [
        'sign',
        'characteristics',
        'ruling_planet',
        'element',
        'quality',
        'career',
        'relationships',
    ];

    public $timestamps = false;

    public static function getForSign(string $sign): ?self
    {
        return static::where('sign', $sign)->first();
    }
}
