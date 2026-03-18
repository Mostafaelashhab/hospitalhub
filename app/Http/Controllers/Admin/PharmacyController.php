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
        $source = $request->get('source', 'local');

        $localDrugs = collect();
        $apiResult = null;
        $savedDrug = null;

        // Always search local DB
        if (strlen($query) >= 2) {
            $localDrugs = Drug::where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('generic_name', 'like', "%{$query}%")
                ->orWhere('category_name', 'like', "%{$query}%")
                ->limit(50)
                ->get();
        } else {
            $localDrugs = Drug::orderBy('name')->limit(50)->get();
        }

        // API search: fetch + save to DB
        if ($source === 'api' && strlen($query) >= 2) {
            $apiResult = $drugInfoService->lookup($query);

            // If success, save to local DB
            if ($apiResult['success'] && !empty($apiResult['data'])) {
                $savedDrug = Drug::importFromApi($query, $apiResult['data']);

                // Refresh local list to include the new drug
                if (!$localDrugs->contains('id', $savedDrug->id)) {
                    $localDrugs->prepend($savedDrug);
                }
            }
        }

        $isConfigured = $drugInfoService->isConfigured();
        $totalDrugs = Drug::count();

        return view('admin.pharmacy.index', compact('localDrugs', 'apiResult', 'query', 'source', 'isConfigured', 'savedDrug', 'totalDrugs'));
    }

    public function show(Drug $drug)
    {
        return view('admin.pharmacy.show', compact('drug'));
    }

    public function apiLookup(Request $request, DrugInfoService $drugInfoService)
    {
        $request->validate(['drug' => 'required|string|min:2|max:100']);

        $result = $drugInfoService->lookup($request->drug);

        // Save to DB on success
        if ($result['success'] && !empty($result['data'])) {
            $drug = Drug::importFromApi($request->drug, $result['data']);
            $result['saved_drug_id'] = $drug->id;
        }

        return response()->json($result);
    }
}
