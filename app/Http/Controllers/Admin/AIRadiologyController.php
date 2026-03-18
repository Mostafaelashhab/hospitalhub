<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientFile;
use App\Services\AIRadiologyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AIRadiologyController extends Controller
{
    protected AIRadiologyService $service;

    public function __construct(AIRadiologyService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the AI Radiology analysis page.
     */
    public function index(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        // Get patient's radiology files
        $radiologyFiles = PatientFile::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->where(function ($q) {
                $q->where('category', 'radiology')
                  ->orWhere('file_type', 'like', 'image/%');
            })
            ->orderByDesc('created_at')
            ->get();

        $isConfigured = $this->service->isConfigured();

        return view('admin.ai-radiology.index', compact('patient', 'radiologyFiles', 'isConfigured'));
    }

    /**
     * Analyze an image (upload or URL).
     */
    public function analyze(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        if (!$this->service->isConfigured()) {
            return back()->with('error', __('app.ai_radiology_not_configured'));
        }

        $request->validate([
            'analysis_type' => 'required|in:file,url,existing',
            'image_file' => 'required_if:analysis_type,file|nullable|image|max:10240',
            'image_url' => 'required_if:analysis_type,url|nullable|url',
            'existing_file_id' => 'required_if:analysis_type,existing|nullable|exists:patient_files,id',
            'message' => 'nullable|string|max:500',
            'language' => 'required|in:en,ar',
        ]);

        $language = $request->input('language', app()->getLocale()) ?? app()->getLocale();
        $message = $request->input('message') ?? '';

        if ($request->analysis_type === 'file') {
            $result = $this->service->analyzeFromFile(
                $request->file('image_file'),
                $message,
                $language
            );
        } elseif ($request->analysis_type === 'url') {
            $result = $this->service->analyzeFromUrl(
                $request->input('image_url'),
                $message,
                $language
            );
        } else {
            // Existing patient file
            $file = PatientFile::where('id', $request->existing_file_id)
                ->where('clinic_id', $clinic->id)
                ->firstOrFail();

            $publicUrl = url(Storage::url($file->file_path));
            $result = $this->service->analyzeFromUrl($publicUrl, $message, $language);
        }

        if ($result['success']) {
            return back()->with([
                'ai_result' => $result['data'],
                'ai_success' => true,
                'analysis_type' => $request->analysis_type,
            ]);
        }

        return back()->with('error', __('app.ai_radiology_error') . ': ' . ($result['error'] ?? 'Unknown error'));
    }

    /**
     * AJAX endpoint for real-time analysis.
     */
    public function analyzeAjax(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        if (!$this->service->isConfigured()) {
            return response()->json(['success' => false, 'error' => 'API not configured'], 422);
        }

        $request->validate([
            'image_url' => 'required|url',
            'message' => 'nullable|string|max:500',
            'language' => 'required|in:en,ar',
        ]);

        $result = $this->service->analyzeFromUrl(
            $request->input('image_url'),
            $request->input('message', ''),
            $request->input('language', 'en')
        );

        return response()->json($result);
    }
}
