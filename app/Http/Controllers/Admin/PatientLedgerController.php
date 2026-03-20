<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientLedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientLedgerController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        // Get patients with their balance (latest ledger entry)
        $query = Patient::where('clinic_id', $clinic->id)
            ->select('patients.*')
            ->addSelect([
                'ledger_balance' => PatientLedgerEntry::select('balance_after')
                    ->whereColumn('patient_id', 'patients.id')
                    ->where('clinic_id', $clinic->id)
                    ->latest()
                    ->limit(1),
            ]);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $filter = $request->get('filter', 'debtors');
        if ($filter === 'debtors') {
            $query->having('ledger_balance', '>', 0);
        } elseif ($filter === 'creditors') {
            $query->having('ledger_balance', '<', 0);
        }

        $patients = $query->orderByDesc('ledger_balance')->paginate(20);

        // Summary stats
        $totalDebt = PatientLedgerEntry::where('clinic_id', $clinic->id)
            ->whereIn('id', function ($q) use ($clinic) {
                $q->select(DB::raw('MAX(id)'))
                    ->from('patient_ledger_entries')
                    ->where('clinic_id', $clinic->id)
                    ->groupBy('patient_id');
            })
            ->where('balance_after', '>', 0)
            ->sum('balance_after');

        $debtorsCount = PatientLedgerEntry::where('clinic_id', $clinic->id)
            ->whereIn('id', function ($q) use ($clinic) {
                $q->select(DB::raw('MAX(id)'))
                    ->from('patient_ledger_entries')
                    ->where('clinic_id', $clinic->id)
                    ->groupBy('patient_id');
            })
            ->where('balance_after', '>', 0)
            ->count();

        return view('admin.ledger.index', compact('patients', 'totalDebt', 'debtorsCount', 'filter'));
    }

    public function show(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $entries = PatientLedgerEntry::where('clinic_id', $clinic->id)
            ->where('patient_id', $patient->id)
            ->with(['invoice', 'creator'])
            ->latest()
            ->paginate(20);

        $balance = PatientLedgerEntry::getBalance($clinic->id, $patient->id);

        return view('admin.ledger.show', compact('patient', 'entries', 'balance'));
    }

    public function addPayment(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:' . implode(',', config('payment.methods')),
            'description' => 'nullable|string|max:500',
        ]);

        PatientLedgerEntry::addCredit(
            $clinic->id,
            $patient->id,
            $validated['amount'],
            $validated['description'] ?? __('app.payment_received'),
            $validated['payment_method'],
            null,
            auth()->id(),
        );

        return back()->with('success', __('app.payment_recorded'));
    }

    public function addDebt(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        PatientLedgerEntry::addDebit(
            $clinic->id,
            $patient->id,
            $validated['amount'],
            $validated['description'] ?? __('app.debt_added'),
            null,
            auth()->id(),
        );

        return back()->with('success', __('app.debt_recorded'));
    }
}
