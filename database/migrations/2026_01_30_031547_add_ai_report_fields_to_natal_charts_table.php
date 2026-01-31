<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('natal_charts', function (Blueprint $table) {
            $table->enum('report_status', ['new', 'processing', 'completed', 'failed'])->default('new');
            $table->json('report_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('natal_charts', function (Blueprint $table) {
            $table->dropColumn(['report_status', 'report_content']);
        });
    }
};
