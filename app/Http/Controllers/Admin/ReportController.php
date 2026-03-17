<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $clinicId = $user->clinic_id;
        $branchId = session('active_branch_id');

        // Date range (default: last 30 days)
        $from = $request->input('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $baseAppointments = Appointment::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->whereBetween('appointment_date', [$from, $to]);

        $baseInvoices = Invoice::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);

        $basePatients = Patient::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId));

        // === Overview Stats ===
        $stats = [
            'total_appointments' => (clone $baseAppointments)->count(),
            'completed_appointments' => (clone $baseAppointments)->where('status', 'completed')->count(),
            'cancelled_appointments' => (clone $baseAppointments)->where('status', 'cancelled')->count(),
            'no_show' => (clone $baseAppointments)->where('status', 'no_show')->count(),
            'new_patients' => (clone $basePatients)->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'total_patients' => (clone $basePatients)->count(),
            'total_revenue' => (clone $baseInvoices)->where('status', 'paid')->sum('total'),
            'pending_revenue' => (clone $baseInvoices)->where('status', 'unpaid')->sum('total'),
            'total_invoices' => (clone $baseInvoices)->count(),
            'paid_invoices' => (clone $baseInvoices)->where('status', 'paid')->count(),
        ];

        // === Monthly Appointments Chart (last 6 months) ===
        $appointmentsChart = Appointment::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->where('appointment_date', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(appointment_date, '%Y-%m') as month, COUNT(*) as total, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // === Monthly Revenue Chart (last 6 months) ===
        $revenueChart = Invoice::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // === Doctor Performance ===
        $doctorPerformance = Appointment::where('appointments.clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('appointments.branch_id', $branchId))
            ->whereBetween('appointment_date', [$from, $to])
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->selectRaw("users.name as doctor_name, COUNT(*) as total_appointments, SUM(CASE WHEN appointments.status = 'completed' THEN 1 ELSE 0 END) as completed, SUM(CASE WHEN appointments.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            ->groupBy('doctors.id', 'users.name')
            ->orderByDesc('total_appointments')
            ->limit(10)
            ->get();

        // === Top Diagnoses ===
        $topDiagnoses = Diagnosis::whereHas('appointment', function ($q) use ($clinicId, $branchId, $from, $to) {
                $q->where('clinic_id', $clinicId)
                    ->when($branchId, fn ($q2) => $q2->where('branch_id', $branchId))
                    ->whereBetween('appointment_date', [$from, $to]);
            })
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // === Payment Methods Distribution ===
        $paymentMethods = Invoice::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->where('status', 'paid')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        // === Appointment Status Distribution ===
        $appointmentStatuses = (clone $baseAppointments)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // === Expenses Data ===
        $baseExpenses = Expense::where('expenses.clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('expenses.branch_id', $branchId))
            ->whereBetween('expense_date', [$from, $to]);

        $stats['total_expenses'] = (clone $baseExpenses)->sum('amount');
        $stats['net_profit'] = $stats['total_revenue'] - $stats['total_expenses'];

        // Expense by category
        $expensesByCategory = (clone $baseExpenses)
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->selectRaw('expense_categories.name_en, expense_categories.name_ar, expense_categories.color, SUM(expenses.amount) as total')
            ->groupBy('expense_categories.id', 'expense_categories.name_en', 'expense_categories.name_ar', 'expense_categories.color')
            ->orderByDesc('total')
            ->get();

        // Monthly Expenses Chart (last 6 months)
        $expensesChart = Expense::where('clinic_id', $clinicId)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->where('expense_date', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports.index', compact(
            'stats', 'appointmentsChart', 'revenueChart', 'expensesChart', 'doctorPerformance',
            'topDiagnoses', 'paymentMethods', 'appointmentStatuses', 'expensesByCategory', 'from', 'to'
        ));
    }
}
