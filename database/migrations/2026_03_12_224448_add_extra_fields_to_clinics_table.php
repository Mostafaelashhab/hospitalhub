<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->unsignedInteger('doctors_count')->nullable()->after('tax_number');
            $table->unsignedInteger('expected_patients_monthly')->nullable()->after('doctors_count');
            $table->string('clinic_size')->nullable()->after('expected_patients_monthly'); // small, medium, large
            $table->string('working_hours_from')->nullable()->after('clinic_size');
            $table->string('working_hours_to')->nullable()->after('working_hours_from');
            $table->json('working_days')->nullable()->after('working_hours_to');
            $table->boolean('has_existing_system')->default(false)->after('working_days');
            $table->string('existing_system_name')->nullable()->after('has_existing_system');
            $table->string('referral_source')->nullable()->after('existing_system_name'); // google, social_media, friend, other
            $table->text('notes')->nullable()->after('referral_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'doctors_count', 'expected_patients_monthly', 'clinic_size',
                'working_hours_from', 'working_hours_to', 'working_days',
                'has_existing_system', 'existing_system_name', 'referral_source', 'notes',
            ]);
        });
    }
};
