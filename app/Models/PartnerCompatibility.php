<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerCompatibility extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'partner_compatibility';

    protected $fillable = [
        'user_id',
        'user_chart_id',
        'partner_user_id',
        'partner_chart_id',
        'partner_name',
        'partner_email',
        'partner_gender',
        'partner_birth_date',
        'partner_birth_time',
        'partner_city_id',
        'verification_token',
        'verified_at',
        'synastry_data',
        'scores',
        'ai_report',
        'ai_report_status',
        'status',
    ];

    protected $casts = [
        'partner_birth_date' => 'date',
        'verified_at' => 'datetime',
        'synastry_data' => 'array',
        'scores' => 'array',
        'ai_report' => 'array',
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userChart(): BelongsTo
    {
        return $this->belongsTo(NatalChart::class, 'user_chart_id');
    }

    public function partnerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_user_id');
    }

    public function partnerChart(): BelongsTo
    {
        return $this->belongsTo(NatalChart::class, 'partner_chart_id');
    }

    public function partnerCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'partner_city_id');
    }

    // Status helpers

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified' || $this->status === 'completed';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Score helpers

    public function getOverallScoreAttribute(): int
    {
        return $this->scores['overall'] ?? $this->synastry_data['score'] ?? 50;
    }

    public function getScoreDescriptionAttribute(): string
    {
        $score = $this->overall_score;

        if ($score >= 85) return 'Идеальная совместимость';
        if ($score >= 70) return 'Отличная совместимость';
        if ($score >= 55) return 'Хорошая совместимость';
        if ($score >= 40) return 'Средняя совместимость';
        if ($score >= 25) return 'Сложная совместимость';

        return 'Сложные уроки';
    }

    public function getScoreColorAttribute(): string
    {
        $score = $this->overall_score;

        if ($score >= 70) return '#22C55E'; // green
        if ($score >= 50) return '#F97316'; // orange
        if ($score >= 30) return '#EAB308'; // yellow

        return '#EF4444'; // red
    }

    /**
     * Get category scores for display (16 categories)
     */
    public function getCategoryScoresAttribute(): array
    {
        $scores = $this->scores ?? [];

        return [
            'overall' => ['label' => 'Общая совместимость', 'value' => $scores['overall'] ?? 50, 'icon' => 'heart'],
            'emotional' => ['label' => 'Эмоциональная связь', 'value' => $scores['emotional'] ?? 50, 'icon' => 'moon'],
            'communication' => ['label' => 'Коммуникация', 'value' => $scores['communication'] ?? 50, 'icon' => 'comment'],
            'romantic' => ['label' => 'Романтика и страсть', 'value' => $scores['romantic'] ?? 50, 'icon' => 'fire'],
            'sexual' => ['label' => 'Сексуальная химия', 'value' => $scores['sexual'] ?? 50, 'icon' => 'bolt'],
            'values' => ['label' => 'Ценности и приоритеты', 'value' => $scores['values'] ?? 50, 'icon' => 'balance-scale'],
            'finance' => ['label' => 'Финансы и материальное', 'value' => $scores['finance'] ?? 50, 'icon' => 'coins'],
            'family' => ['label' => 'Семья и дом', 'value' => $scores['family'] ?? 50, 'icon' => 'home'],
            'spirituality' => ['label' => 'Духовность', 'value' => $scores['spirituality'] ?? 50, 'icon' => 'star'],
            'longterm' => ['label' => 'Долгосрочный потенциал', 'value' => $scores['longterm'] ?? 50, 'icon' => 'infinity'],
            'conflict' => ['label' => 'Разрешение конфликтов', 'value' => $scores['conflict'] ?? 50, 'icon' => 'handshake'],
            'trust' => ['label' => 'Доверие и верность', 'value' => $scores['trust'] ?? 50, 'icon' => 'shield'],
            'support' => ['label' => 'Поддержка и забота', 'value' => $scores['support'] ?? 50, 'icon' => 'hands-helping'],
            'adventure' => ['label' => 'Приключения и развлечения', 'value' => $scores['adventure'] ?? 50, 'icon' => 'plane'],
            'daily' => ['label' => 'Повседневная жизнь', 'value' => $scores['daily'] ?? 50, 'icon' => 'coffee'],
            'career' => ['label' => 'Карьера и амбиции', 'value' => $scores['career'] ?? 50, 'icon' => 'briefcase'],
        ];
    }

    /**
     * Get synastry aspects grouped by nature
     */
    public function getGroupedAspectsAttribute(): array
    {
        $aspects = $this->synastry_data['aspects'] ?? [];

        return [
            'harmony' => array_filter($aspects, fn($a) => ($a['nature'] ?? '') === 'harmony'),
            'tension' => array_filter($aspects, fn($a) => ($a['nature'] ?? '') === 'tension'),
        ];
    }

    /**
     * Check if AI report is available
     */
    public function hasAiReport(): bool
    {
        return !empty($this->ai_report) && $this->ai_report_status === 'completed';
    }

    /**
     * Check if AI report is being generated
     */
    public function isAiReportProcessing(): bool
    {
        return $this->ai_report_status === 'processing' || $this->ai_report_status === 'pending';
    }
}
