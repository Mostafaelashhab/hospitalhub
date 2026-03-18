<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Services\DrugInfoService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index(Request $request, DrugInfoService $drugInfoService)
    {
        $query = $request->get('q', '');
        $source = $request->get('source', 'local'); // local or api

        $localDrugs = collect();
        $apiResult = null;

        // Always search local DB
        if (strlen($query) >= 2) {
            $localDrugs = Drug::where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('category_name', 'like', "%{$query}%")
                ->limit(50)
                ->get();
        } else {
            $localDrugs = Drug::orderBy('name')->limit(50)->get();
        }

        // If user chose API search and has a query
        if ($source === 'api' && strlen($query) >= 2) {
            $apiResult = $drugInfoService->lookup($query);
        }

        $isConfigured = $drugInfoService->isConfigured();

        return view('admin.pharmacy.index', compact('localDrugs', 'apiResult', 'query', 'source', 'isConfigured'));
    }

    public function apiLookup(Request $request, DrugInfoService $drugInfoService)
    {
        $request->validate(['drug' => 'required|string|min:2|max:100']);

        $result = $drugInfoService->lookup($request->drug);

        return response()->json($result);
    }
}
