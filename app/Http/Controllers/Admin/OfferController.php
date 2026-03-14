<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $clinicId = $request->user()->clinic_id;

        $offers = Offer::active()
            ->forClinic($clinicId)
            ->latest()
            ->paginate(15);

        return view('admin.offers.index', compact('offers'));
    }
}
