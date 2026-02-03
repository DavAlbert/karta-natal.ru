<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planet_meanings', function (Blueprint $table) {
            $table->id();
            $table->string('planet'); // sun, moon, mercury, etc.
            $table->text('description'); // Общее описание планеты
            $table->text('strengths'); // Сильные стороны
            $table->text('weaknesses'); // Слабые стороны
            $table->text('career'); // Карьера
            $table->text('relationships'); // Отношения
            $table->text('health'); // Здоровье
            $table->timestamps();
            $table->unique('planet');
        });

        Schema::create('sign_meanings', function (Blueprint $table) {
            $table->id();
            $table->string('sign'); // Овен, Телец, etc.
            $table->text('characteristics'); // Общие характеристики
            $table->text('ruling_planet'); // Управляющая планета
            $table->text('element'); // Стихия
            $table->text('quality'); // Качество (кардинальный, etc.)
            $table->text('career'); // Карьера
            $table->text('relationships'); // Отношения
            $table->timestamps();
            $table->unique('sign');
        });

        Schema::create('house_meanings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('house_number'); // 1-12
            $table->text('general'); // Общее значение дома
            $table->text('keywords'); // Ключевые слова
            $table->text('ruling_planet'); // Управляющая планета
            $table->text('career_work'); // Карьера/работа
            $table->text('money_assets'); // Деньги/активы
            $table->text('relationships'); // Отношения
            $table->text('health'); // Здоровье
            $table->text('home_family'); // Дом/семья
            $table->timestamps();
            $table->unique('house_number');
        });

        Schema::create('aspect_meanings', function (Blueprint $table) {
            $table->id();
            $table->string('aspect_type'); // conjunction, square, trine, etc.
            $table->text('general'); // Общее значение аспекта
            $table->text('harmonious'); // Описание для гармоничных
            $table->text('stressful'); // Описание для напряженных
            $table->text('interpretation'); // Интерпретация
            $table->timestamps();
            $table->unique('aspect_type');
        });

        Schema::create('element_meanings', function (Blueprint $table) {
            $table->id();
            $table->string('element'); // fire, earth, air, water
            $table->text('characteristics'); // Характеристики
            $table->text('strengths'); // Сильные стороны
            $table->text('challenges'); // Сложности
            $table->text('compatibility'); // Совместимость
            $table->timestamps();
            $table->unique('element');
        });

        Schema::create('quality_meanings', function (Blueprint $table) {
            $table->id();
            $table->string('quality'); // cardinal, fixed, mutable
            $table->text('characteristics'); // Характеристики
            $table->text('strengths'); // Сильные стороны
            $table->text('challenges'); // Сложности
            $table->timestamps();
            $table->unique('quality');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_meanings');
        Schema::dropIfExists('element_meanings');
        Schema::dropIfExists('aspect_meanings');
        Schema::dropIfExists('house_meanings');
        Schema::dropIfExists('sign_meanings');
        Schema::dropIfExists('planet_meanings');
    }
};
