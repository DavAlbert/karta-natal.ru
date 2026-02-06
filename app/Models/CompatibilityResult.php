<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CompatibilityResult extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'result_id',
        'access_token',
        'person1_name',
        'person1_email',
        'person2_data',
        'result_data',
        'shared_token',
    ];

    protected $casts = [
        'person2_data' => 'array',
        'result_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get person 1 data.
     */
    public function getPerson1Attribute(): array
    {
        return [
            'name' => $this->person1_name,
            'email' => $this->person1_email,
            'gender' => $this->result_data['person1']['gender'] ?? null,
            'birth_date' => $this->result_data['person1']['birth_date'] ?? null,
            'birth_time' => $this->result_data['person1']['birth_time'] ?? null,
            'birth_place' => $this->result_data['person1']['birth_place'] ?? null,
        ];
    }

    /**
     * Get person 2 data.
     */
    public function getPerson2Attribute(): array
    {
        return $this->person2_data;
    }

    /**
     * Get chart 1 data.
     */
    public function getChart1Attribute(): ?array
    {
        return $this->result_data['chart1'] ?? null;
    }

    /**
     * Get chart 2 data.
     */
    public function getChart2Attribute(): ?array
    {
        return $this->result_data['chart2'] ?? null;
    }

    /**
     * Get synastry data.
     */
    public function getSynastryAttribute(): ?array
    {
        return $this->result_data['synastry'] ?? null;
    }

    /**
     * Get AI report.
     */
    public function getAiReportAttribute(): ?array
    {
        return $this->result_data['ai_report'] ?? null;
    }

    /**
     * Get compatibility score (0-100).
     */
    public function getScoreAttribute(): int
    {
        return $this->synastry['score'] ?? 50;
    }

    /**
     * Get score description.
     */
    public function getScoreDescriptionAttribute(): string
    {
        $score = $this->score;

        if ($score >= 85) return 'Идеальная совместимость';
        if ($score >= 70) return 'Отличная совместимость';
        if ($score >= 55) return 'Хорошая совместимость';
        if ($score >= 40) return 'Средняя совместимость';
        if ($score >= 25) return 'Сложная совместимость';

        return 'Сложные уроки';
    }

    /**
     * Get score color class.
     */
    public function getScoreColorAttribute(): string
    {
        $score = $this->score;

        if ($score >= 70) return 'text-green-500';
        if ($score >= 50) return 'text-yellow-500';
        if ($score >= 30) return 'text-orange-500';

        return 'text-red-500';
    }
}
