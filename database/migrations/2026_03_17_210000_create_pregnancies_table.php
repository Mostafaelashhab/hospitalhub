<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->date('lmp_date');
            $table->date('edd_date');
            $table->enum('status', ['active', 'delivered', 'miscarriage', 'terminated'])->default('active');
            $table->date('delivery_date')->nullable();
            $table->enum('delivery_type', ['normal', 'cesarean', 'assisted'])->nullable();
            $table->enum('baby_gender', ['male', 'female', 'unknown'])->nullable();
            $table->decimal('baby_weight', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregnancies');
    }
};
