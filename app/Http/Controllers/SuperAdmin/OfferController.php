<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Offer;
use App\Models\User;
use App\Notifications\BroadcastNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::with('creator', 'clinics')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            } elseif ($request->status === 'upcoming') {
                $query->where('start_date', '>', now());
            }
        }

        $offers = $query->paginate(15);

        return view('super-admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $clinics = Clinic::where('status', 'active')
            ->orderBy('name_' . app()->getLocale())
            ->get();

        return view('super-admin.offers.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ar' => 'nullable|string|max:2000',
            'type' => 'required|in:discount,drug_offer,promotion',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'for_all_clinics' => 'boolean',
            'clinic_ids' => 'nullable|array',
            'clinic_ids.*' => 'exists:clinics,id',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['for_all_clinics'] = $request->boolean('for_all_clinics', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('offers', 'public');
        }

        unset($validated['clinic_ids']);
        $offer = Offer::create($validated);

        if (!$offer->for_all_clinics && $request->filled('clinic_ids')) {
            $offer->clinics()->sync($request->clinic_ids);
        }

        // Notify clinic admins about the new offer
        $this->notifyClinicAdmins($offer);

        return redirect()->route('super.offers.index')
            ->with('success', __('app.offer_created'));
    }

    public function edit(Offer $offer)
    {
        $clinics = Clinic::where('status', 'active')
            ->orderBy('name_' . app()->getLocale())
            ->get();

        $offer->load('clinics');

        return view('super-admin.offers.edit', compact('offer', 'clinics'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ar' => 'nullable|string|max:2000',
            'type' => 'required|in:discount,drug_offer,promotion',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'for_all_clinics' => 'boolean',
            'clinic_ids' => 'nullable|array',
            'clinic_ids.*' => 'exists:clinics,id',
        ]);

        $validated['for_all_clinics'] = $request->boolean('for_all_clinics', true);

        if ($request->hasFile('image')) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $validated['image'] = $request->file('image')->store('offers', 'public');
        }

        unset($validated['clinic_ids']);
        $offer->update($validated);

        if (!$offer->for_all_clinics && $request->filled('clinic_ids')) {
            $offer->clinics()->sync($request->clinic_ids);
        } else {
            $offer->clinics()->detach();
        }

        return redirect()->route('super.offers.index')
            ->with('success', __('app.offer_updated'));
    }

    public function toggleStatus(Offer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);

        return back()->with('success', __('app.status_updated'));
    }

    public function destroy(Offer $offer)
    {
        if ($offer->image) {
            Storage::disk('public')->delete($offer->image);
        }

        $offer->delete();

        return redirect()->route('super.offers.index')
            ->with('success', __('app.offer_deleted'));
    }

    private function notifyClinicAdmins(Offer $offer): void
    {
        $title = app()->getLocale() === 'ar' ? $offer->title_ar : $offer->title_en;

        if ($offer->for_all_clinics) {
            $admins = User::where('role', 'admin')->whereNotNull('clinic_id')->get();
        } else {
            $clinicIds = $offer->clinics()->pluck('clinics.id');
            $admins = User::where('role', 'admin')->whereIn('clinic_id', $clinicIds)->get();
        }

        $notification = new BroadcastNotification(
            __('app.new_offer') . ': ' . $title,
            $offer->description ?? $title
        );

        foreach ($admins as $admin) {
            try {
                $admin->notify($notification);
            } catch (\Exception $e) {
                // Skip notification failures
            }
        }
    }
}
