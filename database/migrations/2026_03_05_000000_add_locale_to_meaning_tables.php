<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'planet_meanings' => 'planet',
            'sign_meanings' => 'sign',
            'house_meanings' => 'house_number',
            'aspect_meanings' => 'aspect_type',
            'element_meanings' => 'element',
            'quality_meanings' => 'quality',
        ];

        foreach ($tables as $table => $keyColumn) {
            Schema::table($table, function (Blueprint $t) use ($keyColumn) {
                $t->string('locale', 5)->default('ru')->after('id');
                $t->dropUnique([$keyColumn]);
                $t->unique([$keyColumn, 'locale']);
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'planet_meanings' => 'planet',
            'sign_meanings' => 'sign',
            'house_meanings' => 'house_number',
            'aspect_meanings' => 'aspect_type',
            'element_meanings' => 'element',
            'quality_meanings' => 'quality',
        ];

        foreach ($tables as $table => $keyColumn) {
            Schema::table($table, function (Blueprint $t) use ($keyColumn) {
                $t->dropUnique([$keyColumn, 'locale']);
                $t->unique([$keyColumn]);
                $t->dropColumn('locale');
            });
        }
    }
};
