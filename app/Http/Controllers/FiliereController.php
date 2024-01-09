<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;

class FiliereController extends Controller
{
    public function getFilieres()
    {
        $filieres = Filiere::all();
        return response()->json(['status' => 'success', 'data' => $filieres]);

    }
    public function showGroupes($filiereId)
    {
        $filiere = Filiere::with('groupes')->findOrFail($filiereId);

        // bach njib les groupes associés à la filière
        $groupes = $filiere->groupes;

        return response()->json(['status' => 'success', 'data' => ['filiere' => $filiere, 'groupes' => $groupes]]);

    }
}
