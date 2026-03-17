<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;
        $coupons = Coupon::where('clinic_id', $clinic->id)->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'code'                 => 'nullable|string|max:50|unique:coupons,code',
            'name_en'              => 'required|string|max:255',
            'name_ar'              => 'required|string|max:255',
            'type'                 => 'required|in:percentage,fixed',
            'value'                => 'required|numeric|min:0.01',
            'min_amount'           => 'nullable|numeric|min:0',
            'max_discount'         => 'nullable|numeric|min:0',
            'max_uses'             => 'nullable|integer|min:1',
            'max_uses_per_patient' => 'nullable|integer|min:1',
            'valid_from'           => 'nullable|date',
            'valid_to'             => 'nullable|date|after_or_equal:valid_from',
        ]);

        // Auto-generate code if empty
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateCode();
        } else {
            $validated['code'] = strtoupper($validated['code']);
        }

        Coupon::create([
            'clinic_id'            => $clinic->id,
            'code'                 => $validated['code'],
            'name_en'              => $validated['name_en'],
            'name_ar'              => $validated['name_ar'],
            'type'                 => $validated['type'],
            'value'                => $validated['value'],
            'min_amount'           => $validated['min_amount'] ?? null,
            'max_discount'         => $validated['max_discount'] ?? null,
            'max_uses'             => $validated['max_uses'] ?? null,
            'max_uses_per_patient' => $validated['max_uses_per_patient'] ?? null,
            'valid_from'           => $validated['valid_from'] ?? null,
            'valid_to'             => $validated['valid_to'] ?? null,
            'is_active'            => true,
            'used_count'           => 0,
        ]);

        return redirect()->route('dashboard.coupons.index')
            ->with('success', __('app.coupon_created'));
    }

    public function edit(Coupon $coupon)
    {
        abort_if($coupon->clinic_id !== auth()->user()->clinic->id, 403);

        $usageStats = [
            'total_used'    => $coupon->used_count,
            'total_discount' => $coupon->usages()->sum('discount_amount'),
        ];

        return view('admin.coupons.edit', compact('coupon', 'usageStats'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        abort_if($coupon->clinic_id !== auth()->user()->clinic->id, 403);

        $validated = $request->validate([
            'code'                 => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name_en'              => 'required|string|max:255',
            'name_ar'              => 'required|string|max:255',
            'type'                 => 'required|in:percentage,fixed',
            'value'                => 'required|numeric|min:0.01',
            'min_amount'           => 'nullable|numeric|min:0',
            'max_discount'         => 'nullable|numeric|min:0',
            'max_uses'             => 'nullable|integer|min:1',
            'max_uses_per_patient' => 'nullable|integer|min:1',
            'valid_from'           => 'nullable|date',
            'valid_to'             => 'nullable|date|after_or_equal:valid_from',
        ]);

        $coupon->update([
            'code'                 => strtoupper($validated['code']),
            'name_en'              => $validated['name_en'],
            'name_ar'              => $validated['name_ar'],
            'type'                 => $validated['type'],
            'value'                => $validated['value'],
            'min_amount'           => $validated['min_amount'] ?? null,
            'max_discount'         => $validated['max_discount'] ?? null,
            'max_uses'             => $validated['max_uses'] ?? null,
            'max_uses_per_patient' => $validated['max_uses_per_patient'] ?? null,
            'valid_from'           => $validated['valid_from'] ?? null,
            'valid_to'             => $validated['valid_to'] ?? null,
        ]);

        return redirect()->route('dashboard.coupons.index')
            ->with('success', __('app.coupon_updated'));
    }

    public function toggleStatus(Coupon $coupon)
    {
        abort_if($coupon->clinic_id !== auth()->user()->clinic->id, 403);

        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', __('app.status') . ' ' . __('app.updated'));
    }

    public function destroy(Coupon $coupon)
    {
        abort_if($coupon->clinic_id !== auth()->user()->clinic->id, 403);

        $coupon->delete();

        return back()->with('success', __('app.coupon_deleted'));
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code'       => 'required|string',
            'amount'     => 'nullable|numeric|min:0',
            'patient_id' => 'nullable|integer',
        ]);

        $clinic = auth()->user()->clinic;

        $coupon = Coupon::where('clinic_id', $clinic->id)
            ->where('code', strtoupper($request->code))
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid'   => false,
                'message' => __('app.coupon_invalid'),
            ]);
        }

        $amount    = $request->amount ? (float) $request->amount : null;
        $patientId = $request->patient_id ? (int) $request->patient_id : null;

        if (!$coupon->isValid($amount, $patientId)) {
            return response()->json([
                'valid'   => false,
                'message' => __('app.coupon_invalid'),
            ]);
        }

        $discount = $amount ? $coupon->calculateDiscount($amount) : 0;

        return response()->json([
            'valid'           => true,
            'message'         => __('app.coupon_valid'),
            'coupon'          => [
                'id'    => $coupon->id,
                'code'  => $coupon->code,
                'name'  => app()->getLocale() === 'ar' ? $coupon->name_ar : $coupon->name_en,
                'type'  => $coupon->type,
                'value' => $coupon->value,
            ],
            'discount_amount' => $discount,
        ]);
    }

    private function generateCode(): string
    {
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $code  = $part1 . '-' . $part2;
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }
}
