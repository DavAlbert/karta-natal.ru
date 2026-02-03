<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityMeaning extends Model
{
    protected $fillable = [
        'quality',
        'characteristics',
        'strengths',
        'challenges',
    ];

    public $timestamps = false;

    public static function getForQuality(string $quality): ?self
    {
        return static::where('quality', $quality)->first();
    }
}
