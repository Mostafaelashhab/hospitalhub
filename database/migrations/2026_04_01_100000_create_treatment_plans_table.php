<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('estimated_total', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->date('presented_at')->nullable();
            $table->date('accepted_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'patient_id']);
            $table->index(['clinic_id', 'status']);
        });

        Schema::create('treatment_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tooth_number', 5)->nullable();
            $table->string('description');
            $table->decimal('estimated_cost', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();

            $table->index('treatment_plan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_items');
        Schema::dropIfExists('treatment_plans');
    }
};
