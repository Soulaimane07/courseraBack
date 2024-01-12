<?php

namespace App\Http\Controllers;
use App\Models\Module;
use App\Models\Filiere;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function getModules()
    {
        $modules = Module::all();

        return response()->json(['data' => $modules]);
    }

    public function getModule($id)
    {
        $module = Module::where('id', $id)->first();
        return response()->json(['status' => 'success', 'data' => $module]);
    }

    public function showCours($moduleId)
    {
        $module = Module::with('cours')->findOrFail($moduleId);

        // Récupérez les cours associés au module
        $cours = $module->cours;

        return response()->json(['module' => $module, 'data' => $cours]);
    }

    public function ajouterModule(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
        ]);

        $module = Module::create([
            'nom' => $request->input('nom'),
            'filiere_id' => $request->input('filiere_id'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Le module a été ajouté avec succès.',
            'data' => $module,
        ]);
    }

    public function afficherModulesDeFiliere($filiereId)
    {
        $filiere = Filiere::findOrFail($filiereId);
        $modules = $filiere->modules;
        return response()->json([
            'status' => 'success',
            'message' => 'Modules de la filière récupérés avec succès.',
            'data' => $modules,
        ]);
    }

    public function supprimerModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $module->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Le module a été supprimé avec succès.',
        ]);
    }
    public function modifierModule(Request $request, $moduleId)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
        ]);

        $module = Module::findOrFail($moduleId);
        $module->update([
            'nom' => $request->input('nom'),
            'filiere_id' => $request->input('filiere_id'),
        ]);    
    }

    public function getmodulesParFiliere($filiereId)
    {
        $filiere = Filiere::findOrFail($filiereId);
        $modules = $filiere->modules;

        return response()->json([
            'status' => 'success',
            'data' => $modules,
        ]);
    }
}

