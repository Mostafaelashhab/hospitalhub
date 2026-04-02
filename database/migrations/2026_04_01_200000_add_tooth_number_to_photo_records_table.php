<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photo_records', function (Blueprint $table) {
            $table->string('tooth_number', 5)->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('photo_records', function (Blueprint $table) {
            $table->dropColumn('tooth_number');
        });
    }
};
