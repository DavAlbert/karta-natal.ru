<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add chart_status to natal_charts for tracking async processing
        Schema::table('natal_charts', function (Blueprint $table) {
            $table->string('chart_status')->default('completed')->after('type');
            // chart_status: pending, processing, completed, failed
        });

        // Add ai_report_status to partner_compatibility for tracking AI report generation
        Schema::table('partner_compatibility', function (Blueprint $table) {
            $table->string('ai_report_status')->default('pending')->after('ai_report');
            // ai_report_status: pending, processing, completed, failed
        });
    }

    public function down(): void
    {
        Schema::table('natal_charts', function (Blueprint $table) {
            $table->dropColumn('chart_status');
        });

        Schema::table('partner_compatibility', function (Blueprint $table) {
            $table->dropColumn('ai_report_status');
        });
    }
};
