<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AspectMeaning extends Model
{
    protected $fillable = [
        'aspect_type',
        'general',
        'harmonious',
        'stressful',
        'interpretation',
    ];

    public $timestamps = false;

    public static function getForAspect(string $aspectType): ?self
    {
        return static::where('aspect_type', $aspectType)->first();
    }

    public function isHarmonious(): bool
    {
        return in_array($this->aspect_type, ['trine', 'sextile', 'conjunction']);
    }

    public function isStressful(): bool
    {
        return in_array($this->aspect_type, ['square', 'opposition']);
    }
}
