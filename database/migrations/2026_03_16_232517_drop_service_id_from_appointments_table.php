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
        // Migrate existing service_id data to pivot table
        $appointments = \DB::table('appointments')->whereNotNull('service_id')->get();
        foreach ($appointments as $appt) {
            $service = \DB::table('services')->find($appt->service_id);
            if ($service) {
                \DB::table('appointment_service')->insert([
                    'appointment_id' => $appt->id,
                    'service_id' => $appt->service_id,
                    'price' => $service->price ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('doctor_id')->constrained()->nullOnDelete();
        });
    }
};
