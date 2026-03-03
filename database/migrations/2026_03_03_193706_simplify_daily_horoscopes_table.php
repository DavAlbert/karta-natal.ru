<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clear all existing data (we only keep current horoscope per sign/locale)
        DB::table('daily_horoscopes')->truncate();

        Schema::table('daily_horoscopes', function (Blueprint $table) {
            // Drop old unique index
            $table->dropUnique(['date', 'sign', 'locale']);
            // Remove date column
            $table->dropColumn('date');
            // New unique constraint: only one horoscope per sign+locale
            $table->unique(['sign', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::table('daily_horoscopes', function (Blueprint $table) {
            $table->dropUnique(['sign', 'locale']);
            $table->date('date')->after('id');
            $table->unique(['date', 'sign', 'locale']);
        });
    }
};
