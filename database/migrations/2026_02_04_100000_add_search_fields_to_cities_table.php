<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('name_ru')->nullable()->after('name');
            $table->text('alternate_names')->nullable()->after('name_ru');
            $table->string('name_normalized')->nullable()->after('alternate_names');

            $table->index('name_ru');
            $table->index('name_normalized');
            $table->fullText(['name', 'name_ru', 'name_normalized']);
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropFullText(['name', 'name_ru', 'name_normalized']);
            $table->dropIndex(['name_normalized']);
            $table->dropIndex(['name_ru']);
            $table->dropColumn(['name_ru', 'alternate_names', 'name_normalized']);
        });
    }
};
