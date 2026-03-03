<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_horoscopes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('sign', 20)->index(); // aries, taurus, etc.
            $table->string('locale', 5)->default('en')->index();
            $table->json('transit_data')->nullable(); // planetary positions for the day
            $table->json('content'); // overview, love, career, health, lucky_number
            $table->timestamps();

            $table->unique(['date', 'sign', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_horoscopes');
    }
};
