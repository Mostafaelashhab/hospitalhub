<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalReportController extends Controller
{
    public function generate(Diagnosis $diagnosis)
    {
        $clinic = auth()->user()->clinic;
        abort_if($diagnosis->clinic_id !== $clinic->id, 403);

        $diagnosis->load([
            'appointment.patient',
            'appointment.doctor.specialty',
        ]);

        $patient = $diagnosis->appointment->patient;
        $doctor  = $diagnosis->appointment->doctor;

        $pdf = Pdf::loadView('admin.reports.medical-report', [
            'diagnosis' => $diagnosis,
            'patient'   => $patient,
            'doctor'    => $doctor,
            'clinic'    => $clinic,
        ])->setPaper('a4', 'portrait');

        $filename = 'medical-report-' . $patient->id . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    public function generateInvoice(Invoice $invoice)
    {
        $clinic = auth()->user()->clinic;
        abort_if($invoice->clinic_id !== $clinic->id, 403);

        $invoice->load([
            'patient',
            'appointment.doctor',
            'insuranceProvider',
            'items',
        ]);

        $pdf = Pdf::loadView('admin.reports.invoice-pdf', [
            'invoice' => $invoice,
            'clinic'  => $clinic,
        ])->setPaper('a4', 'portrait');

        $filename = 'invoice-' . $invoice->id . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
