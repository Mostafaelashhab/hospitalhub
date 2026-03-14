<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\RechargeRequest;
use App\Models\User;
use App\Notifications\BroadcastNotification;
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

        $rechargeRequests = RechargeRequest::where('clinic_id', $clinic->id)
            ->with('user')
            ->latest()
            ->paginate(10, ['*'], 'recharge_page');

        return view('super-admin.clinics.show', compact('clinic', 'rechargeRequests'));
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

    public function approveRecharge(RechargeRequest $rechargeRequest)
    {
        if ($rechargeRequest->status !== 'pending') {
            return back()->with('error', __('app.request_already_processed'));
        }

        $rechargeRequest->update(['status' => 'approved']);

        // Credit the clinic wallet
        $wallet = $rechargeRequest->clinic->wallet;
        if ($wallet) {
            $wallet->credit(
                $rechargeRequest->points,
                __('app.recharge_approved_desc', ['method' => __('app.payment_' . $rechargeRequest->payment_method)]),
                'recharge',
                $rechargeRequest->id,
            );
        }

        // Notify the clinic admin
        $admins = User::where('clinic_id', $rechargeRequest->clinic_id)->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new BroadcastNotification(
                    __('app.recharge_approved_title'),
                    __('app.recharge_approved_body', ['points' => number_format($rechargeRequest->points)])
                ));
            } catch (\Exception $e) {}
        }

        return back()->with('success', __('app.recharge_request_approved'));
    }

    public function rejectRecharge(Request $request, RechargeRequest $rechargeRequest)
    {
        if ($rechargeRequest->status !== 'pending') {
            return back()->with('error', __('app.request_already_processed'));
        }

        $rechargeRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Notify the clinic admin
        $admins = User::where('clinic_id', $rechargeRequest->clinic_id)->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new BroadcastNotification(
                    __('app.recharge_rejected_title'),
                    __('app.recharge_rejected_body', ['points' => number_format($rechargeRequest->points)])
                ));
            } catch (\Exception $e) {}
        }

        return back()->with('success', __('app.recharge_request_rejected'));
    }

    public function rechargeRequests(Request $request)
    {
        $query = RechargeRequest::with(['clinic', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $rechargeRequests = $query->paginate(20);
        $pendingCount = RechargeRequest::where('status', 'pending')->count();

        return view('super-admin.recharge-requests', compact('rechargeRequests', 'pendingCount'));
    }

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'target' => 'required|in:all,clinic',
            'clinic_id' => 'required_if:target,clinic|nullable|exists:clinics,id',
        ]);

        $notification = new BroadcastNotification($validated['title'], $validated['body']);

        if ($validated['target'] === 'all') {
            // Send to all clinic admins
            $admins = User::where('role', 'admin')->whereNotNull('clinic_id')->get();
            foreach ($admins as $admin) {
                try {
                    $admin->notify($notification);
                } catch (\Exception $e) {
                    // Skip notification failures
                }
            }
            $count = $admins->count();
        } else {
            // Send to specific clinic staff
            $staff = User::where('clinic_id', $validated['clinic_id'])
                ->whereIn('role', ['admin', 'doctor', 'accountant', 'secretary'])
                ->get();
            foreach ($staff as $user) {
                try {
                    $user->notify($notification);
                } catch (\Exception $e) {
                    // Skip notification failures
                }
            }
            $count = $staff->count();
        }

        return back()->with('success', __('app.notification_sent_to', ['count' => $count]));
    }
}
