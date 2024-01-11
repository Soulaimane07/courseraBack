<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Professeur;
use App\Models\Groupe;
use Illuminate\Http\Request;

class CourController extends Controller
{
    public function index()
    {
        $cour = Cour::all();
        return response()->json(['status' => 'success', 'data' => $cour]);
    }

    public function create(Request $req)
    {
        $req->validate([
            'lien' => 'required',
            'titre' => 'required',
            'desc' => 'required',
            'dateDebut' => 'required',
            'dateFin' => 'required',
            'deadline_control'=>'required',
            'module_id'=>'required',
           

        ]);

        $cour = Cour::create([
            'lien' => $req->lien,
            'titre' => $req->titre,
            'desc' => $req->desc,
            'dateDebut' => $req->dateDebut,
            'dateFin' => $req->dateFin,
            'deadline_control' => $req->deadline_control,
            'module_id'=> $req->module_id,

        ]);

        return response()->json(['status' => 'success', 'cour' => $cour]);
    }

    public function update(Request $request, string  $id)
    {
        $courUpdate= Cour::find($id);

        $request->validate([
            'lien' => 'required',
            'titre' => 'required',
            'desc' => 'required',
            'dateDebut' => 'required',
            'dateFin' => 'required',
            'deadline_control'=>'required',
            'module_id'=>'required',

        ]);
        $courUpdate->update([
            'lien' => $request->lien,
            'titre' => $request->titre,
            'desc' => $request->desc,
            'dateDebut' => $request->dateDebut,
            'dateFin' => $request->dateFin,
            'deadline_control' => $request->deadline_control,
            'module_id'=> $request->module_id,
        ]);

        return response()->json([
            'message' => 'cours mis à jour avec succès',
            'data' => $courUpdate
        ]);
    }

    public function destroy(Cour $id)
    {
        if (!$id) {
            return response()->json(['message' => 'Cours introuvable'], 404);
        } else {
            $id->delete();
            return response()->json(['message' => 'Cours supprimé avec succès'], 200);
        }
    }


    public function getCoursEnseignesPourGroupe($professeurId, $groupeId)
    {
        $professeur = Professeur::find($professeurId);
        $groupe = Groupe::find($groupeId);

        if ($professeur && $groupe) {
            $modulesGroupe = $groupe->modules->pluck('id')->toArray();

            $coursEnseignes = $professeur->cours()
                ->whereIn('module_id', $modulesGroupe)
                ->get();

            return response()->json(['data' => $coursEnseignes]);
        } else {
            return response()->json(['message' => 'Professeur ou groupe non trouvé.'], 404);
        }
    }

    

}
