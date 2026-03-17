<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DrugInteraction;
use Illuminate\Http\Request;

class DrugInteractionController extends Controller
{
    public function index()
    {
        $interactions = DrugInteraction::orderBy('severity')->latest()->paginate(20);

        return view('admin.drug-interactions.index', compact('interactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drug_a'            => 'required|string|max:255',
            'drug_b'            => 'required|string|max:255',
            'severity'          => 'required|in:mild,moderate,severe,contraindicated',
            'description_en'    => 'nullable|string|max:1000',
            'description_ar'    => 'nullable|string|max:1000',
            'recommendation_en' => 'nullable|string|max:1000',
            'recommendation_ar' => 'nullable|string|max:1000',
        ]);

        DrugInteraction::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => __('app.interaction_added')]);
        }

        return back()->with('success', __('app.interaction_added'));
    }

    public function destroy(DrugInteraction $interaction)
    {
        $interaction->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => __('app.interaction_deleted')]);
        }

        return back()->with('success', __('app.interaction_deleted'));
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'drugs'   => 'required|array|min:2',
            'drugs.*' => 'required|string|max:255',
        ]);

        $interactions = DrugInteraction::check($validated['drugs']);

        $locale = app()->getLocale();

        $result = array_map(function (DrugInteraction $interaction) use ($locale) {
            return [
                'drug_a'          => $interaction->drug_a,
                'drug_b'          => $interaction->drug_b,
                'severity'        => $interaction->severity,
                'description'     => $locale === 'ar' ? $interaction->description_ar : $interaction->description_en,
                'recommendation'  => $locale === 'ar' ? $interaction->recommendation_ar : $interaction->recommendation_en,
            ];
        }, $interactions);

        return response()->json([
            'found'        => count($result) > 0,
            'interactions' => $result,
            'count'        => count($result),
        ]);
    }
}
