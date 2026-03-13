<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Clinic::with(['specialty', 'wallet']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clinics = $query->latest()->paginate(15);

        return view('super-admin.clinics.index', compact('clinics'));
    }

    public function show(Clinic $clinic)
    {
        $clinic->load(['specialty', 'wallet', 'admin', 'wallet.transactions' => function ($q) {
            $q->latest()->take(20);
        }]);

        return view('super-admin.clinics.show', compact('clinic'));
    }

    public function updateStatus(Request $request, Clinic $clinic)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,inactive',
        ]);

        $clinic->update(['status' => $request->status]);

        return back()->with('success', __('app.status_updated'));
    }

    public function addPoints(Request $request, Clinic $clinic)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $clinic->wallet;
        $wallet->credit(
            $request->points,
            $request->description ?? 'Points added by admin',
        );

        return back()->with('success', __('app.points_added'));
    }

    public function deductPoints(Request $request, Clinic $clinic)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $clinic->wallet;

        if (!$wallet->hasEnoughBalance($request->points)) {
            return back()->with('error', __('app.insufficient_balance'));
        }

        $wallet->debit(
            $request->points,
            $request->description ?? 'Points deducted by admin',
        );

        return back()->with('success', __('app.points_deducted'));
    }
}
