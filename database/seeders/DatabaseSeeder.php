<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate([
            'email' => 'admin@clinicsaas.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Specialties
        $specialties = [
            ['name_en' => 'General Medicine', 'name_ar' => 'طب عام'],
            ['name_en' => 'Dentistry', 'name_ar' => 'طب أسنان'],
            ['name_en' => 'Ophthalmology', 'name_ar' => 'طب عيون'],
            ['name_en' => 'Dermatology', 'name_ar' => 'جلدية'],
            ['name_en' => 'Cardiology', 'name_ar' => 'قلب وأوعية دموية'],
            ['name_en' => 'Orthopedics', 'name_ar' => 'عظام'],
            ['name_en' => 'Pediatrics', 'name_ar' => 'أطفال'],
            ['name_en' => 'ENT', 'name_ar' => 'أنف وأذن وحنجرة'],
            ['name_en' => 'Gynecology', 'name_ar' => 'نساء وتوليد'],
            ['name_en' => 'Urology', 'name_ar' => 'مسالك بولية'],
            ['name_en' => 'Neurology', 'name_ar' => 'مخ وأعصاب'],
            ['name_en' => 'Psychiatry', 'name_ar' => 'طب نفسي'],
            ['name_en' => 'Internal Medicine', 'name_ar' => 'باطنة'],
            ['name_en' => 'Physiotherapy', 'name_ar' => 'علاج طبيعي'],
            ['name_en' => 'Nutrition', 'name_ar' => 'تغذية'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(
                ['name_en' => $specialty['name_en']],
                $specialty
            );
        }
    }
}

