<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->boolean('website_enabled')->default(false)->after('notes');
            $table->string('website_primary_color', 7)->default('#0d9488')->after('website_enabled');
            $table->string('website_secondary_color', 7)->default('#6366f1')->after('website_primary_color');
            $table->text('website_about_en')->nullable()->after('website_secondary_color');
            $table->text('website_about_ar')->nullable()->after('website_about_en');
            $table->json('website_services')->nullable()->after('website_about_ar');
            $table->json('website_social_links')->nullable()->after('website_services');
            $table->string('website_hero_image')->nullable()->after('website_social_links');
            $table->string('website_meta_description')->nullable()->after('website_hero_image');
            $table->boolean('website_show_doctors')->default(true)->after('website_meta_description');
            $table->boolean('website_show_booking')->default(true)->after('website_show_doctors');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'website_enabled',
                'website_primary_color',
                'website_secondary_color',
                'website_about_en',
                'website_about_ar',
                'website_services',
                'website_social_links',
                'website_hero_image',
                'website_meta_description',
                'website_show_doctors',
                'website_show_booking',
            ]);
        });
    }
};
