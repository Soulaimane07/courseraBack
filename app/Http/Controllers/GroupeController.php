<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groupe;
use App\Models\Module;
use App\Models\Professeur;


class GroupeController extends Controller
{
    public function index()
    {
        $groupe = Groupe::all();
        return response()->json(['status' => 'success', 'data' => $groupe]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $req)
    {
        $req->validate([
            'nom' => 'required',
            'filiere_id'=> 'required'

        ]);

        $groupe = Groupe::create([
            'nom' => $req->nom,
            'filiere_id' => $req->filiere_id,

        ]);

        return response()->json(['status' => 'success', 'groupe' => $groupe]);
    }

    
    public function update(Request $request, string  $id)
    {
        $groupeUpdate= Groupe::find($id);

        $request->validate([
            'nom' => 'required',
            'filiere_id'=> 'required'

        ]);
        $groupeUpdate->update([
            'nom' => $request->nom,
            'filiere_id' => $request->filiere_id,

        ]);

        return response()->json([
            'message' => 'groupe mis à jour avec succès',
            'data' => $groupeUpdate
        ]);
    }

   
    public function destroy(Groupe $id)
    {
        // Vérifier si le grp existe
        if (!$id) 
        {
            return response()->json(['message' => 'groupe introuvable'], 404);
        } 
        else 
        {
            $id->delete();
            return response()->json(['message' => 'groupe supprimé avec succès'], 200);
        }
}
// assigner professeur à un groupe:
    public function assignerProfesseurAuGroupe($groupeId, $professeurIds) 
    {
        $groupe = Groupe::find($groupeId);
        $groupe->professeurs()->attach($professeurIds);
        return response()->json(['message' => 'Professeurs assignés avec succès au groupe.']);
    }
// Eliminer un prof d'un grp:
    public function removeProfesseurFromGroupe($groupeId, $professeurId) 
    {
        $groupe = Groupe::find($groupeId);
        if (!$groupe) {
            return response()->json(['message' => 'Groupe introuvable'], 404);
        }
        $professeur = Professeur::find($professeurId);
        if (!$professeur) {
            return response()->json(['message' => 'Professeur introuvable'], 404);
        }
        $groupe->professeurs()->detach($professeur);

        return response()->json(['message' => 'Professeur retiré avec succès .']);
    }
// Afficher les étudiants qui appartiennent à un groupe:
    public function getEtudiants($id)
    {
            $groupe = Groupe::with('etudiants')->find($id);

            if (!$groupe) {
                return response()->json(['message' => 'Groupe introuvable'], 404);
            }

            $etudiants = $groupe->etudiants;

            return response()->json(['etudiants' => $etudiants]);
        }
// Supprimer un etud d'un grp:
    public function removeEtudiantFromGroupe($groupeId, $etudiantId)
    {
        $groupe = Groupe::find($groupeId);
        if (!$groupe) {
            return response()->json(['message' => 'Groupe introuvable'], 404);
        }
        $etudiant = $groupe->etudiants()->find($etudiantId);
        if (!$etudiant) {
            return response()->json(['message' => 'Étudiant introuvable dans ce groupe'], 404);
        }
        $groupe->etudiants()->detach($etudiant);
        return response()->json(['message' => 'Étudiant retiré avec succès du groupe.']);
    }


//Afficher les modules d'un groupe:
    public function showModules($groupeId)
    {
        // Récupérez le groupe par son id avec ses modules:
        $groupe = Groupe::findOrFail($groupeId);
        $modules = $groupe->modules;

        // Supprimez la relation chargée par with('modules') du groupe: hit fl'affichage kaytl3o les modules deux fois
        unset($groupe->modules);
        return response()->json(['groupe' => $groupe, 'modules' => $modules]);
    }
    //Associer un module à un grp!
    public function associateModuleGrp($groupeId, $moduleId)
    {
        // Récupérez le professeur et le module par leurs id:
        $grp = Groupe::findOrFail($groupeId);
        $module = Module::findOrFail($moduleId);
        // Associez le module au prof
        $grp->modules()->attach($module);
        return response()->json(['message' => 'Module associé avec succès']);
    }

}
