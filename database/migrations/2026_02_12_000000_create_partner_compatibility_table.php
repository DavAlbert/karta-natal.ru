<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old compatibility_results table
        Schema::dropIfExists('compatibility_results');

        // Create new partner_compatibility table
        Schema::create('partner_compatibility', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Initiator
            $table->uuid('user_chart_id'); // Initiator's natal chart
            $table->foreignId('partner_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->uuid('partner_chart_id')->nullable(); // Partner's natal chart (after verification)
            $table->string('partner_name');
            $table->string('partner_email');
            $table->enum('partner_gender', ['male', 'female']);
            $table->date('partner_birth_date');
            $table->time('partner_birth_time')->nullable();
            $table->foreignId('partner_city_id')->constrained('cities');
            $table->string('verification_token', 64)->unique();
            $table->timestamp('verified_at')->nullable();
            $table->json('synastry_data')->nullable(); // Synastry aspects
            $table->json('scores')->nullable(); // 16 category scores
            $table->json('ai_report')->nullable(); // AI-generated report
            $table->enum('status', ['pending', 'verified', 'completed'])->default('pending');
            $table->timestamps();

            $table->foreign('user_chart_id')->references('id')->on('natal_charts')->onDelete('cascade');
            $table->foreign('partner_chart_id')->references('id')->on('natal_charts')->onDelete('cascade');

            $table->index(['user_id', 'created_at']);
            $table->index('verification_token');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_compatibility');

        // Recreate old compatibility_results table for rollback
        Schema::create('compatibility_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('result_id', 12)->unique();
            $table->string('access_token', 64)->unique();
            $table->string('person1_name', 255);
            $table->string('person1_email', 255);
            $table->json('person2_data');
            $table->json('result_data');
            $table->string('shared_token', 64)->nullable()->unique();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('access_token');
        });
    }
};
