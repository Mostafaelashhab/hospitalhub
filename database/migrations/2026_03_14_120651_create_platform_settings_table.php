<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed defaults
        DB::table('platform_settings')->insert([
            ['key' => 'point_price', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'free_mode_enabled', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'free_mode_until', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
