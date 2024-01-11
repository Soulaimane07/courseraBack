<?php

namespace App\Http\Controllers;

use App\Models\Professeur;
use App\Models\Cour;
use App\Models\Module;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfesseurController extends Controller
{
// afficher un seul prof:
    public function showOne($id)
    {
        $professeur = Professeur::with('cours')->find($id);

        if (!$professeur) {
            return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.'], 404);
        }

        return response()->json(['status' => 'success', 'professeur' => $professeur]);
    }

//aficher tous les professeurs:
    public function showAll()
    {
        $professeurs = Professeur::with('cours','groupes')->get();

        return response()->json(['status' => 'success', 'data' => $professeurs]);
    }

//ajouter un nouveau prof:
    public function create(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required','email','regex:/@emsi\.ma$/i',
            'password' => 'required',
        ]);

        $professeur = Professeur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json(['status' => 'success', 'professeur' => $professeur]);
    }

// modifier les infos :
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required','email','regex:/@emsi\.ma$/i',
            'password' => 'required',
        ]);

        $professeur = Professeur::findOrFail($id);
        if (!$professeur) {
            return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
        }
        // Mettre à jour les attributs du modèle
        $professeur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json(['status' => 'success', 'professeur' => $professeur]);
    }

// Supprimer un prof:
    public function destroy($id)
    {
        $professeur = Professeur::find($id);

        if (!$professeur) {
            return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
        }
        $professeur->delete();

        return response()->json(['status' => 'success', 'message' => 'Professeur supprimé avec succès']);
    }

// assigner cours au prof:
// public function assignCours(Request $request, $professeurId)
// {
//     $professeur = Professeur::find($professeurId);

//     if (!$professeur) {
//         return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
//     }

//     $coursIds = $request->input('cours_ids');

//     $professeur->cours()->attach($coursIds);

//     return response()->json(['status' => 'success', 'professeur' => $professeur]);
// }



// public function removeCours(Request $request, $professeurId)
// {
//     $professeur = Professeur::find($professeurId);

//     if (!$professeur) {
//         return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
//     }

//     $coursIdToRemove = $request->input('cours_id');

//     // Supprimez le cours spécifique de la table pivot
//     DB::table('cours_professeur')
//         ->where('professeur_id', $professeurId)
//         ->where('cours_id', $coursIdToRemove)
//         ->delete();

//     $coursActuels = $professeur->cours;

//     return response()->json(['status' => 'success', 'cours' => $coursActuels]);
// }

//     public function getCours($profId)
// {
//     $professeur = Professeur::with('cours')->find($profId);

//     if (!$professeur) {
//         return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
//     }

//     $coursProfesseur = $professeur->cours;

//     return response()->json(['status' => 'success', 'cours' => $coursProfesseur]);
// }

    public function getGrp_Prof($profId)
    {
        $professeur = Professeur::with('groupes')->find($profId);

        if (!$professeur) {
            return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
        }

        $groupesProfesseur = $professeur->groupes;
        return response()->json(['status' => 'success', 'data' => $groupesProfesseur]);
    }

//obtenir les modules associés à un professeur: 
    public function showModules($profId)
    {
        $prof = Professeur::findOrFail($profId);

        // recuperer les modules associés à ce professeur
        $modules = $prof->modules;
        unset($prof->modules);
        return response()->json(['prof' => $prof, 'data' => $modules]);
    }

//associer modules a prof:
    public function associateModule($profId, $moduleId)
    {
        // recuperer le professeur et le module par leurs id:
        $prof = Professeur::findOrFail($profId);
        $module = Module::findOrFail($moduleId);
        // Associer le module au professeur
        $prof->modules()->attach($module);
        return response()->json(['message' => 'Module associé avec succès']);
    }

//afficher les cours d'un module associé à un prof
    public function showCours($professeurId, $moduleId)
        {
            $professeur = Professeur::with(['modules' => function ($query) use ($moduleId) {
                $query->where('id', $moduleId);
            }])->findOrFail($professeurId);

            if ($professeur->modules->isNotEmpty()) {
                // recup les cours associés au module
                $cours = $professeur->modules->first()->cours;

                return response()->json(['professeur' => $professeur, 'cours' => $cours]);
            } else {
                return response()->json(['message' => 'Le professeur n\'est associé à aucun module'], 404);
            }
        }


        public function assignCours(Request $request, $professeurId){
            $professeur = Professeur::find($professeurId);
        
            if (!$professeur) {
                return response()->json(['status' => 'error', 'message' => 'Professeur non trouvé.']);
            }
        
            $coursIds = $request->input('cours_ids');
        
            $professeur->cours()->attach($coursIds);
        
            return response()->json(['status' => 'success', 'professeur' => $professeur]);
        }










}
