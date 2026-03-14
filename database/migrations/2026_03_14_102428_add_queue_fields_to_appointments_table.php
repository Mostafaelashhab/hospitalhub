<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedInteger('queue_number')->nullable()->after('recurrence_count');
            $table->enum('queue_status', ['waiting', 'called', 'in_room', 'done', 'skipped'])->nullable()->after('queue_number');
            $table->timestamp('checked_in_at')->nullable()->after('queue_status');
            $table->timestamp('called_at')->nullable()->after('checked_in_at');

            $table->index(['doctor_id', 'appointment_date', 'queue_number']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['doctor_id', 'appointment_date', 'queue_number']);
            $table->dropColumn(['queue_number', 'queue_status', 'checked_in_at', 'called_at']);
        });
    }
};
