<?php

namespace App\Http\Controllers;

use App\Models\Clinic;

class ClinicPageController extends Controller
{
    public function show(string $slug)
    {
        abort(404);
    }
}
