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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('insurance_provider_id')->nullable()->after('appointment_id')->constrained()->nullOnDelete();
            $table->decimal('insurance_coverage', 10, 2)->default(0)->after('discount');
            $table->decimal('patient_share', 10, 2)->default(0)->after('insurance_coverage');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['insurance_provider_id']);
            $table->dropColumn(['insurance_provider_id', 'insurance_coverage', 'patient_share']);
        });
    }
};
