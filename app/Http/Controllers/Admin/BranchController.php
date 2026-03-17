<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;
        $branches = $clinic->branches()->orderByDesc('is_main')->latest()->get();

        // If no branches, auto-create main branch from clinic data
        if ($branches->isEmpty()) {
            $main = $clinic->branches()->create([
                'name' => app()->getLocale() === 'ar' ? ($clinic->name_ar ?: $clinic->name_en) : ($clinic->name_en ?: $clinic->name_ar),
                'phone' => $clinic->phone,
                'address' => app()->getLocale() === 'ar' ? $clinic->address_ar : $clinic->address_en,
                'city' => $clinic->city,
                'is_main' => true,
                'is_active' => true,
            ]);
            $branches = collect([$main]);
        }

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $clinic = auth()->user()->clinic;

        // Ensure main branch exists first
        if (!$clinic->branches()->where('is_main', true)->exists()) {
            $clinic->branches()->create([
                'name' => $clinic->name_en ?: $clinic->name_ar,
                'phone' => $clinic->phone,
                'address' => $clinic->address_en ?: $clinic->address_ar,
                'city' => $clinic->city,
                'is_main' => true,
                'is_active' => true,
            ]);
        }

        $clinic->branches()->create(array_merge($validated, [
            'is_main' => false,
            'is_active' => true,
        ]));

        return redirect()->route('dashboard.branches.index')
            ->with('success', __('app.branch_created'));
    }

    public function edit(Branch $branch)
    {
        $clinic = auth()->user()->clinic;
        abort_if($branch->clinic_id !== $clinic->id, 403);

        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $clinic = auth()->user()->clinic;
        abort_if($branch->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('dashboard.branches.index')
            ->with('success', __('app.branch_updated'));
    }

    public function switchBranch(Branch $branch)
    {
        $clinic = auth()->user()->clinic;
        abort_if($branch->clinic_id !== $clinic->id, 403);
        abort_if(!auth()->user()->hasAccessToBranch($branch->id), 403);

        session(['active_branch_id' => $branch->id]);

        return back()->with('success', __('app.branch_switched', ['branch' => $branch->name]));
    }

    public function destroy(Branch $branch)
    {
        $clinic = auth()->user()->clinic;
        abort_if($branch->clinic_id !== $clinic->id, 403);
        abort_if($branch->is_main, 403, __('app.cannot_delete_main_branch'));

        $branch->delete();

        return redirect()->route('dashboard.branches.index')
            ->with('success', __('app.branch_deleted'));
    }
}
