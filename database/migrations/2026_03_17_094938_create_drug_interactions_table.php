<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drug_interactions', function (Blueprint $table) {
            $table->id();
            $table->string('drug_a');
            $table->string('drug_b');
            $table->enum('severity', ['mild', 'moderate', 'severe', 'contraindicated']);
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('recommendation_en')->nullable();
            $table->text('recommendation_ar')->nullable();
            $table->timestamps();

            $table->index(['drug_a', 'drug_b']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drug_interactions');
    }
};
