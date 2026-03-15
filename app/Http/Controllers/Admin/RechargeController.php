<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\RechargeRequest;
use App\Models\User;
use App\Notifications\RechargeRequested;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;
        $clinic->load('wallet');

        $requests = RechargeRequest::where('clinic_id', $clinic->id)
            ->latest()
            ->paginate(15);

        $paymentAccounts = [
            'instapay_name' => PlatformSetting::get('instapay_account_name'),
            'instapay_number' => PlatformSetting::get('instapay_account_number'),
            'vodafone_name' => PlatformSetting::get('vodafone_account_name'),
            'vodafone_number' => PlatformSetting::get('vodafone_account_number'),
        ];

        return view('admin.recharge.index', compact('clinic', 'requests', 'paymentAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:50',
            'payment_method' => 'required|in:instapay,vodafone_cash,collector',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $clinic = auth()->user()->clinic;

        $rechargeRequest = RechargeRequest::create([
            'clinic_id' => $clinic->id,
            'user_id' => auth()->id(),
            'points' => $validated['points'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        // Notify super admins
        $superAdmins = User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $admin) {
            try {
                $admin->notify(new RechargeRequested($rechargeRequest));
            } catch (\Exception $e) {
                // Skip
            }
        }

        return back()->with('success', __('app.recharge_request_sent'));
    }
}
