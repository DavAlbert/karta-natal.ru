<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'birth_time' => 'datetime',
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

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    // ========== Static Meanings from DB ==========

    public function getPlanetMeaning(string $planet): ?PlanetMeaning
    {
        return PlanetMeaning::getForPlanet($planet);
    }

    public function getSignMeaning(string $sign): ?SignMeaning
    {
        return SignMeaning::getForSign($sign);
    }

    public function getHouseMeaning(int $house): ?HouseMeaning
    {
        return HouseMeaning::getForHouse($house);
    }

    public function getAspectMeaning(string $aspectType): ?AspectMeaning
    {
        return AspectMeaning::getForAspect($aspectType);
    }

    public function getElementMeaning(string $element): ?ElementMeaning
    {
        return ElementMeaning::getForElement($element);
    }

    public function getQualityMeaning(string $quality): ?QualityMeaning
    {
        return QualityMeaning::getForQuality($quality);
    }

    // ========== AI-Generated Analysis ==========

    public function hasAiReport(): bool
    {
        return $this->report_status === 'completed' && !empty($this->report_content);
    }

    public function getAiSection(string $section): ?array
    {
        if (!$this->hasAiReport()) {
            return null;
        }
        return $this->report_content[$section] ?? null;
    }

    public function getAiCharacterAnalysis(): ?string
    {
        return $this->getAiSection('character') ?? $this->getAiSection('identity');
    }

    public function getAiCareerAnalysis(): ?string
    {
        return $this->getAiSection('career');
    }

    public function getAiLoveAnalysis(): ?string
    {
        return $this->getAiSection('love');
    }

    public function getAiKarmaAnalysis(): ?string
    {
        return $this->getAiSection('karma');
    }

    public function getAiForecast(): ?string
    {
        return $this->getAiSection('forecast');
    }

    // ========== Combined Analysis ==========

    public function getCombinedCharacterAnalysis(): string
    {
        $parts = [];

        // AI-generated if available
        if ($aiText = $this->getAiCharacterAnalysis()) {
            $parts[] = $aiText;
        }

        // Add static sign meaning
        $sunSign = $this->chart_data['planets']['sun']['sign'] ?? 'Овен';
        if ($signMeaning = $this->getSignMeaning($sunSign)) {
            $parts[] = $signMeaning->characteristics;
        }

        // Add static planet meaning
        if ($sunMeaning = $this->getPlanetMeaning('sun')) {
            $parts[] = $sunMeaning->description;
        }

        return implode("\n\n", array_filter($parts));
    }

    public function getPlanetHouseText(string $planet, int $house): string
    {
        $parts = [];

        // Static planet meaning
        if ($planetMeaning = $this->getPlanetMeaning($planet)) {
            $parts[] = $planetMeaning->description;
        }

        // Static house meaning
        if ($houseMeaning = $this->getHouseMeaning($house)) {
            $parts[] = $houseMeaning->general;
        }

        return implode("\n\n", $parts);
    }
}
