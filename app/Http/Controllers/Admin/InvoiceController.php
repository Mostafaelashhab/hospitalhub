<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceUpdated;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $branchId = BranchHelper::activeBranchId();

        $query = Invoice::where('clinic_id', $clinic->id)
            ->with(['patient', 'appointment']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', fn($p) => $p->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhere('id', $search);
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($method = $request->get('payment_method')) {
            $query->where('payment_method', $method);
        }

        $invoices = $query->latest()->paginate(15);

        $statsQuery = Invoice::where('clinic_id', $clinic->id);
        if ($branchId) {
            $statsQuery->where('branch_id', $branchId);
        }

        $stats = [
            'total' => (clone $statsQuery)->sum('total'),
            'paid' => (clone $statsQuery)->where('status', 'paid')->sum('total'),
            'unpaid' => (clone $statsQuery)->where('status', 'unpaid')->sum('total'),
            'count' => (clone $statsQuery)->count(),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    public function show(Invoice $invoice)
    {
        $clinic = auth()->user()->clinic;
        abort_if($invoice->clinic_id !== $clinic->id, 403);

        $invoice->load(['patient', 'appointment.doctor', 'insuranceProvider', 'items']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $clinic = auth()->user()->clinic;
        abort_if($invoice->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid,partial,refunded',
            'payment_method' => 'nullable|in:cash,card,bank_transfer,instapay',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $discount = $validated['discount'] ?? $invoice->discount;
        $subtotal = $invoice->amount - $discount;

        // Recalculate insurance coverage
        $insuranceCoverage = 0;
        $patientShare = max(0, $subtotal);

        if ($invoice->insurance_provider_id) {
            $provider = $invoice->insuranceProvider;
            if ($provider) {
                $insuranceCoverage = $subtotal * ($provider->coverage_percentage / 100);
                if ($provider->max_coverage && $insuranceCoverage > $provider->max_coverage) {
                    $insuranceCoverage = $provider->max_coverage;
                }
                $insuranceCoverage = max(0, round($insuranceCoverage, 2));
                $patientShare = max(0, $subtotal - $insuranceCoverage);
            }
        }

        $invoice->update([
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'] ?? $invoice->payment_method,
            'discount' => $discount,
            'insurance_coverage' => $insuranceCoverage,
            'patient_share' => $patientShare,
            'total' => max(0, $patientShare),
            'notes' => $validated['notes'] ?? $invoice->notes,
        ]);

        // Notify clinic admins about invoice update
        $admins = User::where('clinic_id', $clinic->id)
            ->where('id', '!=', request()->user()->id)
            ->whereIn('role', ['admin', 'accountant'])
            ->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new InvoiceUpdated($invoice, $validated['status']));
            } catch (\Exception $e) {
                // Skip notification failures
            }
        }

        return redirect()->route('dashboard.invoices.show', $invoice)
            ->with('success', __('app.invoice_updated'));
    }
}
