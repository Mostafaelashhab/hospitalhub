<?php

namespace App\Http\Controllers\Admin;

use App\Data\GrowthStandards;
use App\Http\Controllers\Controller;
use App\Models\Patient;

class GrowthChartController extends Controller
{
    public function show(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        // Calculate age in months
        $ageInMonths = null;
        if ($patient->date_of_birth) {
            $ageInMonths = (int) $patient->date_of_birth->diffInMonths(now());
            $ageInMonths = min($ageInMonths, 60); // cap display range at 60 months
        }

        // Get vital signs with weight or height recorded
        $vitalSigns = $patient->vitalSigns()
            ->where(function ($q) {
                $q->whereNotNull('weight')->orWhereNotNull('height');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Build patient measurement data points
        $weightMeasurements = [];
        $heightMeasurements = [];
        $bmiMeasurements    = [];

        foreach ($vitalSigns as $vital) {
            // Age of patient at time of measurement in months
            if (! $patient->date_of_birth) {
                continue;
            }

            $measurementAgeMonths = (int) $patient->date_of_birth->diffInMonths($vital->created_at);

            // Only include measurements within 0–60 months range
            if ($measurementAgeMonths < 0 || $measurementAgeMonths > 60) {
                continue;
            }

            if ($vital->weight !== null) {
                $weightMeasurements[] = [
                    'x'    => $measurementAgeMonths,
                    'y'    => (float) $vital->weight,
                    'date' => $vital->created_at->format('Y-m-d'),
                ];
            }

            if ($vital->height !== null) {
                $heightMeasurements[] = [
                    'x'    => $measurementAgeMonths,
                    'y'    => (float) $vital->height,
                    'date' => $vital->created_at->format('Y-m-d'),
                ];
            }

            // BMI = weight (kg) / (height in meters)²
            if ($vital->weight !== null && $vital->height !== null && (float) $vital->height > 0) {
                $heightM = (float) $vital->height / 100;
                $bmi     = round((float) $vital->weight / ($heightM * $heightM), 1);
                $bmiMeasurements[] = [
                    'x'    => $measurementAgeMonths,
                    'y'    => $bmi,
                    'date' => $vital->created_at->format('Y-m-d'),
                ];
            }
        }

        $gender = $patient->gender === 'female' ? 'female' : 'male';

        // WHO reference data
        $weightStandards = GrowthStandards::weightForAge($gender);
        $heightStandards = GrowthStandards::heightForAge($gender);
        $bmiStandards    = GrowthStandards::bmiForAge($gender);

        // Last recorded measurement for summary card
        $lastVital = $patient->vitalSigns()
            ->where(function ($q) {
                $q->whereNotNull('weight')->orWhereNotNull('height');
            })
            ->latest()
            ->first();

        // Determine whether to show BMI chart (patient >= 24 months old)
        $showBmiChart = $ageInMonths !== null && $ageInMonths >= 24;

        return view('admin.growth-chart.show', compact(
            'patient',
            'ageInMonths',
            'weightMeasurements',
            'heightMeasurements',
            'bmiMeasurements',
            'weightStandards',
            'heightStandards',
            'bmiStandards',
            'lastVital',
            'showBmiChart',
        ));
    }
}
