<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'clinic_id',
        'branch_id',
        'name',
        'phone',
        'email',
        'gender',
        'date_of_birth',
        'address',
        'medical_history',
        'allergies',
        'blood_type',
        'national_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function files()
    {
        return $this->hasMany(PatientFile::class);
    }

    public function photoRecords()
    {
        return $this->hasMany(\App\Models\PhotoRecord::class);
    }

    public function insurances()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function activeInsurance()
    {
        return $this->hasOne(PatientInsurance::class)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            })
            ->latest();
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function chronicDiseases()
    {
        return $this->hasMany(ChronicDisease::class);
    }

    public function medications()
    {
        return $this->hasMany(PatientMedication::class);
    }

    public function medicalNotes()
    {
        return $this->hasMany(MedicalNote::class);
    }

    public function latestVitals()
    {
        return $this->hasOne(VitalSign::class)->latest();
    }

    public function activeChronicDiseases()
    {
        return $this->hasMany(ChronicDisease::class)->where('is_active', true);
    }

    public function activeMedications()
    {
        return $this->hasMany(PatientMedication::class)->where('is_active', true);
    }

    public function pregnancies()
    {
        return $this->hasMany(Pregnancy::class);
    }

    public function activePregnancy()
    {
        return $this->hasOne(Pregnancy::class)->where('status', 'active')->latest();
    }

    public function dentalCharts()
    {
        return $this->hasMany(DentalChart::class);
    }

    public function latestDentalChart()
    {
        return $this->hasOne(DentalChart::class)->latest();
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }
}
