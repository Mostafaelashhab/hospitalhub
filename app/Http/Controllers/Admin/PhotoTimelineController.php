<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PhotoRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoTimelineController extends Controller
{
    public function index(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $photos = PhotoRecord::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->orderBy('taken_at', 'asc')
            ->orderBy('sort_order', 'asc')
            ->get();

        $grouped = $photos->groupBy('category');

        $categories = ['skin', 'teeth', 'face', 'body', 'wound', 'other'];

        return view('admin.photo-timeline.index', compact('patient', 'photos', 'grouped', 'categories'));
    }

    public function store(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $request->validate([
            'photos'    => 'required|array|max:10',
            'photos.*'  => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category'  => 'required|in:skin,teeth,face,body,wound,other',
            'label'     => 'nullable|string|max:255',
            'taken_at'  => 'required|date',
            'notes'     => 'nullable|string|max:1000',
        ]);

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store("patient-photos/{$patient->id}", 'public');

            PhotoRecord::create([
                'patient_id' => $patient->id,
                'clinic_id'  => $clinic->id,
                'doctor_id'  => auth()->user()->doctor?->id ?? null,
                'category'   => $request->category,
                'label'      => $request->label,
                'photo_path' => $path,
                'taken_at'   => $request->taken_at,
                'notes'      => $request->notes,
                'sort_order' => 0,
            ]);
        }

        return back()->with('success', __('app.photo_uploaded'));
    }

    public function compare(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $photos = PhotoRecord::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->orderBy('taken_at', 'asc')
            ->get();

        $categories = $photos->pluck('category')->unique()->values();

        return view('admin.photo-timeline.compare', compact('patient', 'photos', 'categories'));
    }

    public function destroy(PhotoRecord $photo)
    {
        $clinic = auth()->user()->clinic;
        abort_if($photo->clinic_id !== $clinic->id, 403);

        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return back()->with('success', __('app.photo_deleted'));
    }
}
