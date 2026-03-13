<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\Request;

class DrugSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $drugs = Drug::where('name', 'like', "%{$query}%")
            ->orWhere('name_ar', 'like', "%{$query}%")
            ->limit(15)
            ->get(['id', 'name', 'name_ar', 'price', 'image']);

        return response()->json($drugs->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'name_ar' => $d->name_ar,
            'price' => $d->price,
            'image' => $d->image ? asset('storage/' . $d->image) : null,
        ]));
    }
}
