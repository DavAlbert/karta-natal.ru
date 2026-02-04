<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    protected $fillable = [
        'name',
        'name_ru',
        'alternate_names',
        'name_normalized',
        'country',
        'latitude',
        'longitude',
        'timezone_gmt',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function natalCharts()
    {
        return $this->hasMany(NatalChart::class);
    }

    /**
     * Get display name (Russian if available, otherwise ASCII)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name_ru ?? $this->name;
    }

    /**
     * Scope for smart city search (Russian + Latin + transliteration)
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if (empty($term)) {
            return $query;
        }

        // Normalize: replace hyphens with spaces and vice versa for flexible matching
        $termLower = mb_strtolower($term);
        $termWithSpaces = str_replace('-', ' ', $termLower);
        $termWithHyphens = str_replace(' ', '-', $termLower);

        $searchTerms = array_unique([
            '%' . $termLower . '%',
            '%' . $termWithSpaces . '%',
            '%' . $termWithHyphens . '%',
        ]);

        // Also create Latin transliteration of Cyrillic input
        $latinTerm = $this->transliterateToLatin($term);
        $latinTermSpaces = str_replace('-', ' ', $latinTerm);

        // Also create Cyrillic transliteration of Latin input
        $cyrillicTerm = $this->transliterateToСyrillic($term);
        $cyrillicTermHyphens = str_replace(' ', '-', $cyrillicTerm);

        // Build query with selectRaw to add relevance score
        return $query->where(function ($q) use ($searchTerms, $latinTerm, $latinTermSpaces, $cyrillicTerm, $cyrillicTermHyphens, $termLower) {
            // Primary matches (name, name_ru, name_normalized)
            foreach ($searchTerms as $searchTerm) {
                $q->orWhereRaw('LOWER(name) LIKE ?', [$searchTerm])
                  ->orWhereRaw('LOWER(name_ru) LIKE ?', [$searchTerm])
                  ->orWhereRaw('LOWER(name_normalized) LIKE ?', [$searchTerm]);
            }
            // Transliterated matches
            $q->orWhereRaw('LOWER(name) LIKE ?', ['%' . $latinTerm . '%'])
              ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $latinTermSpaces . '%'])
              ->orWhereRaw('LOWER(name_ru) LIKE ?', ['%' . $cyrillicTerm . '%'])
              ->orWhereRaw('LOWER(name_ru) LIKE ?', ['%' . $cyrillicTermHyphens . '%']);

            // Only search alternate_names if term is 3+ chars to reduce noise
            if (mb_strlen($termLower) >= 3) {
                $q->orWhere(function ($subQ) use ($termLower) {
                    $subQ->whereRaw('LOWER(alternate_names) LIKE ?', ['%,' . $termLower . ',%'])
                         ->orWhereRaw('LOWER(alternate_names) LIKE ?', [$termLower . ',%'])
                         ->orWhereRaw('LOWER(alternate_names) LIKE ?', ['%,' . $termLower]);
                });
            }
        });
    }

    /**
     * Transliterate Cyrillic to Latin
     */
    private function transliterateToLatin(string $text): string
    {
        $map = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd',
            'Е' => 'e', 'Ё' => 'yo', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i',
            'Й' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',
            'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't',
            'У' => 'u', 'Ф' => 'f', 'Х' => 'kh', 'Ц' => 'ts', 'Ч' => 'ch',
            'Ш' => 'sh', 'Щ' => 'shch', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '',
            'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya',
        ];

        return strtolower(strtr($text, $map));
    }

    /**
     * Transliterate Latin to Cyrillic
     */
    private function transliterateToСyrillic(string $text): string
    {
        $text = mb_strtolower($text);

        // Multi-character mappings first (order matters!)
        $map = [
            'shch' => 'щ', 'sch' => 'щ',
            'zh' => 'ж', 'kh' => 'х', 'ts' => 'ц', 'ch' => 'ч',
            'sh' => 'ш', 'yu' => 'ю', 'ya' => 'я', 'yo' => 'ё',
            'ye' => 'е', 'yi' => 'и',
            'a' => 'а', 'b' => 'б', 'v' => 'в', 'g' => 'г', 'd' => 'д',
            'e' => 'е', 'z' => 'з', 'i' => 'и', 'y' => 'ы',
            'k' => 'к', 'l' => 'л', 'm' => 'м', 'n' => 'н', 'o' => 'о',
            'p' => 'п', 'r' => 'р', 's' => 'с', 't' => 'т', 'u' => 'у',
            'f' => 'ф', 'h' => 'х', 'c' => 'ц', 'w' => 'в', 'x' => 'кс',
            'j' => 'й', 'q' => 'к',
        ];

        foreach ($map as $latin => $cyrillic) {
            $text = str_replace($latin, $cyrillic, $text);
        }

        return $text;
    }
}
