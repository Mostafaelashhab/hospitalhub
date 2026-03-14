<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('recurrence_group_id')->nullable()->after('notes');
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'biweekly', 'monthly'])->default('none')->after('recurrence_group_id');
            $table->unsignedTinyInteger('recurrence_count')->default(1)->after('recurrence_type');

            $table->index('recurrence_group_id');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['recurrence_group_id']);
            $table->dropColumn(['recurrence_group_id', 'recurrence_type', 'recurrence_count']);
        });
    }
};
