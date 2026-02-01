<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NatalChart extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'purpose',
        'birth_date',
        'birth_time',
        'birth_place',
        'city_id',
        'latitude',
        'longitude',
        'timezone',
        'chart_data',
        'type',
        'report_status',
        'report_content',
        'access_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_time' => 'datetime', // or 'timestamp' depending on preference, actually 'datetime' usually works for TIME cols in Laravel if paired with Carbon
        'chart_data' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'report_content' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
