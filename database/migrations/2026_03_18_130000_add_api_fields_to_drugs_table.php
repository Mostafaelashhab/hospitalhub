<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drugs', function (Blueprint $table) {
            $table->string('generic_name')->nullable()->after('name_ar');
            $table->string('manufacturer')->nullable()->after('generic_name');
            $table->string('drug_class')->nullable()->after('manufacturer');
            $table->text('description')->nullable()->after('drug_class');
            $table->text('indications')->nullable()->after('description');
            $table->text('dosage')->nullable()->after('indications');
            $table->text('side_effects')->nullable()->after('dosage');
            $table->text('contraindications')->nullable()->after('side_effects');
            $table->text('interactions')->nullable()->after('contraindications');
            $table->text('warnings')->nullable()->after('interactions');
            $table->string('pregnancy_category')->nullable()->after('warnings');
            $table->text('storage_info')->nullable()->after('pregnancy_category');
            $table->json('api_raw_data')->nullable()->after('storage_info');
            $table->timestamp('api_fetched_at')->nullable()->after('api_raw_data');

            // Make external_id nullable for API-imported drugs
            $table->unsignedBigInteger('external_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('drugs', function (Blueprint $table) {
            $table->dropColumn([
                'generic_name', 'manufacturer', 'drug_class', 'description',
                'indications', 'dosage', 'side_effects', 'contraindications',
                'interactions', 'warnings', 'pregnancy_category', 'storage_info',
                'api_raw_data', 'api_fetched_at',
            ]);
        });
    }
};
