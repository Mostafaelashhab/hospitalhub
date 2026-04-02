<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Doctors always go to doctor portal
        if ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        $clinic = $user->clinic;

        if (!$clinic) {
            return redirect()->route('home');
        }

        $clinic->load('wallet');
        $clinic->seedDefaultPermissions();
        $branchId = BranchHelper::activeBranchId();

        $patientsQuery = $clinic->patients();
        $doctorsQuery = $clinic->doctors();
        $appointmentsQuery = $clinic->appointments();
        $invoicesQuery = $clinic->invoices();

        if ($branchId) {
            $patientsQuery->where('branch_id', $branchId);
            $doctorsQuery->where('branch_id', $branchId);
            $appointmentsQuery->where('branch_id', $branchId);
            $invoicesQuery->where('branch_id', $branchId);
        }

        $stats = [
            'patients_count' => $patientsQuery->count(),
            'doctors_count' => $doctorsQuery->count(),
            'appointments_today' => (clone $appointmentsQuery)->where('appointment_date', today())->count(),
            'appointments_month' => (clone $appointmentsQuery)->whereMonth('appointment_date', now()->month)->count(),
            'wallet_balance' => $clinic->wallet->balance ?? 0,
            'invoices_unpaid' => $invoicesQuery->where('status', 'unpaid')->count(),
        ];

        $todayQuery = $clinic->appointments()
            ->with(['patient', 'doctor'])
            ->where('appointment_date', today());

        if ($branchId) {
            $todayQuery->where('branch_id', $branchId);
        }

        $todayAppointments = $todayQuery->orderBy('appointment_time')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('clinic', 'stats', 'todayAppointments'));
    }
}
