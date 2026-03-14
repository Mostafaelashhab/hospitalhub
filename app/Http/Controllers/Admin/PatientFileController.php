<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientFileController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,dicom,dcm',
            'category' => 'required|in:lab_result,radiology,prescription,report,id_document,insurance,other',
            'notes' => 'nullable|string|max:500',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store("patient-files/{$patient->id}", 'public');

            PatientFile::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'uploaded_by' => auth()->id(),
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'notes' => $request->notes,
            ]);
        }

        return back()->with('success', __('app.files_uploaded'));
    }

    public function download(PatientFile $patientFile)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patientFile->clinic_id !== $clinic->id, 403);

        return Storage::disk('public')->download($patientFile->file_path, $patientFile->name);
    }

    public function destroy(PatientFile $patientFile)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patientFile->clinic_id !== $clinic->id, 403);

        Storage::disk('public')->delete($patientFile->file_path);
        $patientFile->delete();

        return back()->with('success', __('app.file_deleted'));
    }
}
