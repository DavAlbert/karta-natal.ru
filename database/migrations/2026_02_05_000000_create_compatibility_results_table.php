<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('compatibility_results');
    }
};
