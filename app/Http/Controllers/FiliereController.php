<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;

class FiliereController extends Controller
{
    public function getFilieres()
    {
        $filieres = Filiere::all();

        return response()->json(['data' => $filieres]);
    }
    public function showGroupes($filiereId)
    {
        $filiere = Filiere::with('groupes')->findOrFail($filiereId);

        // bach njib les groupes associés à la filière
        $groupes = $filiere->groupes;

        return response()->json(['filiere' => $filiere, 'data' => $groupes]);
    }
    public function ajouterFiliere(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $filiere = Filiere::create([
            'nom' => $request->input('nom'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'La filière a été ajoutée avec succès.',
            'data' => $filiere,
        ]);
    }

    public function modifierFiliere(Request $request, $filiereId)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $filiere = Filiere::findOrFail($filiereId);
        $filiere->update([
            'nom' => $request->input('nom'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'La filière a été modifiée avec succès.',
            'data' => $filiere,
        ]);
    }

    public function supprimerFiliere($filiereId)
    {
        $filiere = Filiere::findOrFail($filiereId);
        $filiere->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'La filière a été supprimée avec succès.',
        ]);
    }
}
