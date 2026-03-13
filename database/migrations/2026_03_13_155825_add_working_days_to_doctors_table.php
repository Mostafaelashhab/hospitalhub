<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->json('working_days')->nullable()->after('bio');
            $table->time('working_from')->nullable()->after('working_days');
            $table->time('working_to')->nullable()->after('working_from');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['working_days', 'working_from', 'working_to']);
        });
    }
};
