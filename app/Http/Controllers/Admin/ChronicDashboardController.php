<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class ChronicDashboardController extends Controller
{
    public function show(Patient $patient)
    {
        // Ensure the patient belongs to the current clinic
        $clinicId = auth()->user()->clinic_id;
        if ($patient->clinic_id !== $clinicId) {
            abort(403);
        }

        // Get all vital signs for the last 12 months, ordered by date
        $vitalSigns = $patient->vitalSigns()
            ->where('created_at', '>=', now()->subMonths(12))
            ->orderBy('created_at', 'asc')
            ->get();

        // Get the last 20 readings max for charts
        $chartVitals = $patient->vitalSigns()
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        // Get active chronic diseases
        $chronicDiseases = $patient->activeChronicDiseases()->get();

        // Get active medications
        $activeMedications = $patient->activeMedications()->get();

        // Latest vital sign reading
        $latestVital = $patient->vitalSigns()->latest()->first();

        // Calculate trends for the last 5 readings
        $trendVitals = $patient->vitalSigns()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $trends = [
            'bp'     => $this->calculateBpTrend($trendVitals),
            'sugar'  => $this->calculateTrend($trendVitals, 'blood_sugar'),
            'weight' => $this->calculateTrend($trendVitals, 'weight'),
            'hr'     => $this->calculateTrend($trendVitals, 'heart_rate'),
        ];

        // Status colors/labels for latest readings
        $bpStatus    = $this->getBpStatus($latestVital);
        $sugarStatus = $this->getSugarStatus($latestVital);
        $hrStatus    = $this->getHrStatus($latestVital);

        // Format data for Chart.js
        $chartLabels = $chartVitals->map(fn($v) => $v->created_at->format('M d'))->toArray();

        $chartData = [
            'labels'    => $chartLabels,
            'systolic'  => $chartVitals->map(fn($v) => $v->blood_pressure_systolic ? (float) $v->blood_pressure_systolic : null)->toArray(),
            'diastolic' => $chartVitals->map(fn($v) => $v->blood_pressure_diastolic ? (float) $v->blood_pressure_diastolic : null)->toArray(),
            'sugar'     => $chartVitals->map(fn($v) => $v->blood_sugar ? (float) $v->blood_sugar : null)->toArray(),
            'weight'    => $chartVitals->map(fn($v) => $v->weight ? (float) $v->weight : null)->toArray(),
            'heart_rate'=> $chartVitals->map(fn($v) => $v->heart_rate ? (float) $v->heart_rate : null)->toArray(),
        ];

        // Alerts
        $alerts = $this->buildAlerts($latestVital);

        // Last check-up date
        $lastCheckup = $latestVital?->created_at;

        // BMI for latest vital
        $latestBmi = null;
        if ($latestVital && $latestVital->weight && $latestVital->height) {
            $h = $latestVital->height / 100;
            $latestBmi = round($latestVital->weight / ($h * $h), 1);
        }

        return view('admin.chronic-dashboard.show', compact(
            'patient',
            'vitalSigns',
            'chronicDiseases',
            'activeMedications',
            'latestVital',
            'trends',
            'bpStatus',
            'sugarStatus',
            'hrStatus',
            'chartData',
            'alerts',
            'lastCheckup',
            'latestBmi'
        ));
    }

    // ----------------------------------------------------------------
    // Trend helpers
    // ----------------------------------------------------------------

    private function calculateTrend($vitals, string $field): string
    {
        $values = $vitals->pluck($field)->filter()->values();
        if ($values->count() < 2) {
            return 'stable';
        }
        // Compare average of last 2 vs previous 2
        $recent = $values->take(2)->avg();
        $older  = $values->skip(2)->avg();
        if ($older === null) {
            return 'stable';
        }
        $diff = $recent - $older;
        if (abs($diff) < 3) {
            return 'stable';
        }
        return $diff < 0 ? 'improving' : 'worsening';
    }

    private function calculateBpTrend($vitals): string
    {
        $systolics = $vitals->pluck('blood_pressure_systolic')->filter()->values();
        if ($systolics->count() < 2) {
            return 'stable';
        }
        $recent = $systolics->take(2)->avg();
        $older  = $systolics->skip(2)->avg();
        if ($older === null) {
            return 'stable';
        }
        $diff = $recent - $older;
        if (abs($diff) < 5) {
            return 'stable';
        }
        // Lower BP is improving for hypertension patients
        return $diff < 0 ? 'improving' : 'worsening';
    }

    // ----------------------------------------------------------------
    // Status helpers
    // ----------------------------------------------------------------

    private function getBpStatus($vital): array
    {
        if (!$vital || !$vital->blood_pressure_systolic) {
            return ['label' => 'normal', 'color' => 'green'];
        }
        $sys = (float) $vital->blood_pressure_systolic;
        $dia = (float) $vital->blood_pressure_diastolic;
        if ($sys >= 140 || $dia >= 90) {
            return ['label' => 'high', 'color' => 'red'];
        }
        if ($sys >= 130 || $dia >= 85) {
            return ['label' => 'borderline', 'color' => 'yellow'];
        }
        return ['label' => 'normal', 'color' => 'green'];
    }

    private function getSugarStatus($vital): array
    {
        if (!$vital || !$vital->blood_sugar) {
            return ['label' => 'normal', 'color' => 'green'];
        }
        $sugar = (float) $vital->blood_sugar;
        if ($sugar > 200) {
            return ['label' => 'high', 'color' => 'red'];
        }
        if ($sugar > 140) {
            return ['label' => 'borderline', 'color' => 'yellow'];
        }
        return ['label' => 'normal', 'color' => 'green'];
    }

    private function getHrStatus($vital): array
    {
        if (!$vital || !$vital->heart_rate) {
            return ['label' => 'normal', 'color' => 'green'];
        }
        $hr = (float) $vital->heart_rate;
        if ($hr < 50 || $hr > 110) {
            return ['label' => 'high', 'color' => 'red'];
        }
        if ($hr < 60 || $hr > 100) {
            return ['label' => 'borderline', 'color' => 'yellow'];
        }
        return ['label' => 'normal', 'color' => 'green'];
    }

    private function buildAlerts($vital): array
    {
        $alerts = [];
        if (!$vital) {
            return $alerts;
        }

        // Blood Pressure
        if ($vital->blood_pressure_systolic && $vital->blood_pressure_diastolic) {
            $sys = (float) $vital->blood_pressure_systolic;
            $dia = (float) $vital->blood_pressure_diastolic;
            if ($sys >= 140 || $dia >= 90) {
                $alerts[] = [
                    'type'    => 'danger',
                    'key'     => 'bp_elevated',
                    'reading' => intval($sys) . '/' . intval($dia) . ' mmHg',
                ];
            } elseif ($sys >= 130 || $dia >= 85) {
                $alerts[] = [
                    'type'    => 'warning',
                    'key'     => 'bp_elevated',
                    'reading' => intval($sys) . '/' . intval($dia) . ' mmHg',
                ];
            }
        }

        // Blood Sugar
        if ($vital->blood_sugar) {
            $sugar = (float) $vital->blood_sugar;
            if ($sugar > 200) {
                $alerts[] = [
                    'type'    => 'danger',
                    'key'     => 'sugar_high',
                    'reading' => $sugar . ' mg/dL',
                ];
            } elseif ($sugar > 140) {
                $alerts[] = [
                    'type'    => 'warning',
                    'key'     => 'sugar_high',
                    'reading' => $sugar . ' mg/dL',
                ];
            }
        }

        return $alerts;
    }
}
