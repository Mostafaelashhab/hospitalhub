<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('status', 'active')->count(),
            'pending_clinics' => Clinic::where('status', 'pending')->count(),
            'suspended_clinics' => Clinic::where('status', 'suspended')->count(),
            'total_users' => User::where('role', '!=', 'super_admin')->count(),
        ];

        $recentClinics = Clinic::with(['specialty', 'wallet'])
            ->latest()
            ->take(10)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentClinics'));
    }
}
