<?php

namespace App\Http\Controllers;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function getModules()
    {
        $modules = Module::all();
        return response()->json(['status' => 'success', 'data' => $modules]);
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
}
