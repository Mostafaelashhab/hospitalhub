<?php

namespace App\Helpers;

use App\Models\Branch;

class BranchHelper
{
    public static function activeBranchId(): ?int
    {
        $user = auth()->user();
        if (!$user || !$user->clinic_id) {
            return null;
        }

        $branchId = session('active_branch_id');

        if ($branchId) {
            // Verify the branch belongs to the user's clinic, is active, and user has access
            $exists = Branch::where('id', $branchId)
                ->where('clinic_id', $user->clinic_id)
                ->where('is_active', true)
                ->exists();

            if ($exists && $user->hasAccessToBranch($branchId)) {
                return $branchId;
            }

            // Invalid branch in session, clear it
            session()->forget('active_branch_id');
        }

        // Fallback: first allowed branch for this user
        $allowedBranchIds = $user->getAllowedBranchIds();

        $query = Branch::where('clinic_id', $user->clinic_id)
            ->where('is_active', true);

        if ($allowedBranchIds !== null) {
            $query->whereIn('id', $allowedBranchIds);
        }

        // Prefer main branch
        $branch = $query->orderByDesc('is_main')->first();

        if ($branch) {
            session(['active_branch_id' => $branch->id]);
            return $branch->id;
        }

        return null;
    }

    /**
     * Get branches the current user can access (for branch switcher dropdown).
     */
    public static function accessibleBranches()
    {
        $user = auth()->user();
        if (!$user || !$user->clinic_id) {
            return collect();
        }

        $query = Branch::where('clinic_id', $user->clinic_id)
            ->where('is_active', true);

        $allowedBranchIds = $user->getAllowedBranchIds();
        if ($allowedBranchIds !== null) {
            $query->whereIn('id', $allowedBranchIds);
        }

        return $query->orderByDesc('is_main')->get();
    }
}
