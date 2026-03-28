<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicWallet;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Clinic stats
        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('status', 'active')->count(),
            'pending_clinics' => Clinic::where('status', 'pending')->count(),
            'suspended_clinics' => Clinic::where('status', 'suspended')->count(),
            'total_users' => User::where('role', '!=', 'super_admin')->count(),
        ];

        // Platform-wide stats
        $stats['total_patients'] = Patient::count();
        $stats['total_doctors'] = Doctor::count();
        $stats['total_appointments'] = Appointment::count();
        $stats['appointments_today'] = Appointment::where('appointment_date', today())->count();
        $stats['appointments_month'] = Appointment::whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)->count();

        // Financial
        $stats['total_wallet_balance'] = ClinicWallet::sum('balance');
        $stats['revenue_month'] = Invoice::whereIn('status', ['paid', 'partial'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('paid_amount');
        $stats['revenue_total'] = Invoice::whereIn('status', ['paid', 'partial'])->sum('paid_amount');
        $stats['invoices_unpaid'] = Invoice::where('status', 'unpaid')->count();

        // New clinics this month vs last month
        $stats['new_clinics_month'] = Clinic::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $stats['new_clinics_last_month'] = Clinic::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)->count();
        $stats['new_patients_month'] = Patient::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        // Recent clinics
        $recentClinics = Clinic::with(['specialty', 'wallet'])
            ->latest()
            ->take(10)
            ->get();

        // Top clinics by patients
        $topClinicsByPatients = Clinic::withCount('patients')
            ->where('status', 'active')
            ->orderByDesc('patients_count')
            ->take(5)
            ->get();

        // Top clinics by appointments this month
        $topClinicsByAppointments = Clinic::withCount(['appointments' => function ($q) {
                $q->whereMonth('appointment_date', now()->month)
                  ->whereYear('appointment_date', now()->year);
            }])
            ->where('status', 'active')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();

        // Clinics registration trend (last 12 months)
        $registrationTrend = Clinic::where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $monthlyTrend[] = [
                'label' => $date->format('M'),
                'count' => $registrationTrend[$key] ?? 0,
            ];
        }

        // Recent users (not super admin)
        $recentUsers = User::where('role', '!=', 'super_admin')
            ->with('clinic')
            ->latest()
            ->take(8)
            ->get();

        // Appointment status breakdown (this month)
        $appointmentStatuses = Appointment::whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('super-admin.dashboard', compact(
            'stats', 'recentClinics', 'topClinicsByPatients',
            'topClinicsByAppointments', 'monthlyTrend', 'recentUsers',
            'appointmentStatuses'
        ));
    }
}
