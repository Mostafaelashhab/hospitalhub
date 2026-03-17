<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $clinicId = auth()->user()->clinic_id;

        $query = AuditLog::with('user')
            ->where('clinic_id', $clinicId)
            ->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(30)->withQueryString();

        // Get distinct users for the filter dropdown
        $users = AuditLog::where('clinic_id', $clinicId)
            ->whereNotNull('user_id')
            ->select('user_id', 'user_name')
            ->distinct()
            ->orderBy('user_name')
            ->get();

        // Get distinct model types for filter
        $modelTypes = AuditLog::where('clinic_id', $clinicId)
            ->whereNotNull('model_type')
            ->distinct()
            ->pluck('model_type')
            ->sort()
            ->values();

        return view('admin.audit-logs.index', compact('logs', 'users', 'modelTypes'));
    }
}
