<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change role column from enum to string to support custom roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'patient'");

        // Clinic role permissions - each clinic defines what each role can do
        Schema::create('clinic_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->string('role', 50);
            $table->string('permission', 100);
            $table->timestamps();

            $table->unique(['clinic_id', 'role', 'permission']);
            $table->index(['clinic_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_role_permissions');
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','employee','patient') NOT NULL DEFAULT 'patient'");
    }
};
