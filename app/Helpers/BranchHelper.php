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
            // Verify the branch belongs to the user's clinic and is active
            $exists = Branch::where('id', $branchId)
                ->where('clinic_id', $user->clinic_id)
                ->where('is_active', true)
                ->exists();

            if ($exists) {
                return $branchId;
            }

            // Invalid branch in session, clear it
            session()->forget('active_branch_id');
        }

        // Fallback to main branch
        $mainBranch = Branch::where('clinic_id', $user->clinic_id)
            ->where('is_main', true)
            ->first();

        if ($mainBranch) {
            session(['active_branch_id' => $mainBranch->id]);
            return $mainBranch->id;
        }

        return null;
    }
}
