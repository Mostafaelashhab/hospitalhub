<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained('branches')->nullOnDelete();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained('branches')->nullOnDelete();
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained('branches')->nullOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained('branches')->nullOnDelete();
        });

        Schema::table('diagnoses', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('diagnoses', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
