<?php

namespace App\Http\Controllers;
use App\Models\Annee;
use Illuminate\Http\Request;

class AnneeController extends Controller
{
    public function getAnnees()
    {
        $annees = Annee::all();

        return response()->json(['status' => 'success', 'data' => $annees]);
    }
}
