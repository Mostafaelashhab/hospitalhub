<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pregnancy_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained()->cascadeOnDelete();
            $table->date('visit_date');
            $table->integer('gestational_week');
            $table->decimal('weight', 5, 1)->nullable();
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->decimal('fundal_height', 4, 1)->nullable();
            $table->integer('fetal_heart_rate')->nullable();
            $table->string('presentation')->nullable();
            $table->text('notes')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregnancy_visits');
    }
};
