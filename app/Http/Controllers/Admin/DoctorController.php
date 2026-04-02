<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $branchId = BranchHelper::activeBranchId();

        $query = $clinic->doctors();

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
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string|max:1000',
        ]);

        $clinic = auth()->user()->clinic;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'clinic_id' => $clinic->id,
            'role' => 'doctor',
            'phone' => $validated['phone'],
            'is_active' => true,
        ]);

        $this->ensureDefaultPermissions($clinic->id, 'doctor');

        $dentistry = Specialty::where('name_en', 'Dentistry')->first();

        $clinic->doctors()->create([
            'branch_id' => BranchHelper::activeBranchId(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'specialty_id' => $dentistry->id,
            'consultation_fee' => $validated['consultation_fee'],
            'bio' => $validated['bio'] ?? null,
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.doctors.index')
            ->with('success', __('app.doctor_created'));
    }

    public function show(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $doctor->load(['appointments' => function ($q) {
            $q->with('patient')->latest('appointment_date')->limit(10);
        }]);

        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $doctor->load('services');
        $specialtyServices = Service::where('specialty_id', $doctor->specialty_id)
            ->where('is_active', true)->get();

        $branches = $clinic->branches()->where('is_active', true)->get();
        if ($doctor->user) {
            $doctor->user->load('branches');
        }

        return view('admin.doctors.edit', compact('doctor', 'specialtyServices', 'branches'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $emailRule = 'required|email|max:255';
        if ($doctor->user_id) {
            $emailRule .= '|unique:users,email,' . $doctor->user_id;
        } else {
            $emailRule .= '|unique:users,email';
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => $emailRule,
            'password' => 'nullable|string|min:6',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'services' => 'nullable|array',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.is_active' => 'nullable|boolean',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
        ]);

        $doctor->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'consultation_fee' => $validated['consultation_fee'],
            'bio' => $validated['bio'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Sync User account
        if ($doctor->user_id) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_active' => $validated['is_active'] ?? true,
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $doctor->user->update($userData);
        } else {
            // Create User account for existing doctors that don't have one
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'password123'),
                'clinic_id' => $clinic->id,
                'role' => 'doctor',
                'phone' => $validated['phone'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
            $doctor->update(['user_id' => $user->id]);
            $this->ensureDefaultPermissions($clinic->id, 'doctor');
        }

        // Sync branch access for the doctor's user account
        if ($doctor->user) {
            $doctor->user->branches()->sync($validated['branch_ids'] ?? []);
        }

        // Sync service prices
        if (!empty($validated['services'])) {
            $syncData = [];
            foreach ($validated['services'] as $serviceId => $data) {
                $price = $data['price'] ?? null;
                if ($price !== null && $price !== '') {
                    $syncData[$serviceId] = [
                        'price' => $price,
                        'is_active' => $data['is_active'] ?? false,
                    ];
                }
            }
            $doctor->services()->sync($syncData);
        }

        return redirect()->route('dashboard.doctors.show', $doctor)
            ->with('success', __('app.doctor_updated'));
    }

    public function toggleStatus(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $newStatus = !$doctor->is_active;
        $doctor->update(['is_active' => $newStatus]);

        // Sync User account status
        if ($doctor->user_id) {
            $doctor->user->update(['is_active' => $newStatus]);
        }

        return back()->with('success', __('app.status_updated'));
    }

    public function destroy(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        // Delete the linked user account too
        if ($doctor->user_id) {
            $doctor->user->delete();
        }

        $doctor->delete();

        return redirect()->route('dashboard.doctors.index')
            ->with('success', __('app.doctor_deleted'));
    }

    private function ensureDefaultPermissions(int $clinicId, string $role): void
    {
        $defaults = config("permissions.defaults.{$role}", []);
        if (empty($defaults)) {
            return;
        }

        $existing = DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinicId)
            ->where('role', $role)
            ->pluck('permission')
            ->toArray();

        if (empty($existing)) {
            $records = collect($defaults)->map(fn($p) => [
                'clinic_id' => $clinicId,
                'role' => $role,
                'permission' => $p,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            DB::table('clinic_role_permissions')->insert($records);
        }
    }

    public function schedule(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $schedules = $doctor->schedules()->orderByRaw("FIELD(day, 'sat','sun','mon','tue','wed','thu','fri')")->orderBy('start_time')->get();

        $days = [
            'sat' => app()->getLocale() === 'ar' ? 'السبت' : 'Saturday',
            'sun' => app()->getLocale() === 'ar' ? 'الأحد' : 'Sunday',
            'mon' => app()->getLocale() === 'ar' ? 'الإثنين' : 'Monday',
            'tue' => app()->getLocale() === 'ar' ? 'الثلاثاء' : 'Tuesday',
            'wed' => app()->getLocale() === 'ar' ? 'الأربعاء' : 'Wednesday',
            'thu' => app()->getLocale() === 'ar' ? 'الخميس' : 'Thursday',
            'fri' => app()->getLocale() === 'ar' ? 'الجمعة' : 'Friday',
        ];

        return view('admin.doctors.schedule', compact('doctor', 'schedules', 'days'));
    }

    public function updateSchedule(Request $request, Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        $request->validate([
            'schedules' => 'nullable|array',
            'schedules.*.day' => 'required|in:sat,sun,mon,tue,wed,thu,fri',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.slot_duration' => 'required|integer|in:15,20,30,45,60',
        ]);

        // Delete old schedules and insert new
        $doctor->schedules()->delete();

        if ($request->schedules) {
            foreach ($request->schedules as $schedule) {
                $doctor->schedules()->create([
                    'day' => $schedule['day'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'slot_duration' => $schedule['slot_duration'],
                    'is_active' => true,
                ]);
            }
        }

        return back()->with('success', __('app.schedule_updated'));
    }
}
