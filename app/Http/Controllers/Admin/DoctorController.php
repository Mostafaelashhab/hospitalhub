<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $branchId = BranchHelper::activeBranchId();

        $query = $clinic->doctors()->with('specialty');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $doctors = $query->latest()->paginate(15);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialties = Specialty::where('is_active', true)->get();

        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string|max:1000',
            'create_account' => 'nullable|boolean',
        ];

        if ($request->boolean('create_account')) {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:6';
        }

        $validated = $request->validate($rules);

        $clinic = auth()->user()->clinic;
        $userId = null;

        if ($request->boolean('create_account') && $validated['email']) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'clinic_id' => $clinic->id,
                'role' => 'doctor',
                'phone' => $validated['phone'],
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        $clinic->doctors()->create([
            'branch_id' => BranchHelper::activeBranchId(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'specialty_id' => $validated['specialty_id'],
            'consultation_fee' => $validated['consultation_fee'],
            'bio' => $validated['bio'] ?? null,
            'user_id' => $userId,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.doctors.index')
            ->with('success', __('app.doctor_created'));
    }

    public function show(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $doctor->load(['specialty', 'appointments' => function ($q) {
            $q->with('patient')->latest('appointment_date')->limit(10);
        }]);

        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $specialties = Specialty::where('is_active', true)->get();

        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $doctor->update($validated);

        return redirect()->route('dashboard.doctors.show', $doctor)
            ->with('success', __('app.doctor_updated'));
    }

    public function toggleStatus(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $doctor->update(['is_active' => !$doctor->is_active]);

        return back()->with('success', __('app.status_updated'));
    }
}
