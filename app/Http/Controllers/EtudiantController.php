<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
use App\Models\Etudiant;
class EtudiantController extends Controller
{
    public function index()
    {
        $etudiant = Etudiant::all();
        return response()->json(['status' => 'success', 'data' => $etudiant]);

    }

    public function create(Request $req)
    {
        $req->validate([
            'CIN' => 'required',
            'nom' => 'required',
            'prenom' => 'required',
            'dateNaissance' => 'required',
            'numTele' => 'required',
            'email' => 'required','email','regex:/@emsi\.ma$/i',
            'password'=>'required',
            'groupe_id' => 'required',
        ]);
        
        $etud = Etudiant::create([
            'CIN' => $req->CIN,
            'nom' => $req->nom,
            'prenom' => $req->prenom,
            'dateNaissance' => $req->dateNaissance,
            'numTele' => $req->numTele,
            'email' => $req->email,
            'password' => bcrypt($req->input('password')),
           'groupe_id' => $req->groupe_id,

        ]);

        return response()->json(['status' => 'success', 'etudiant' => $etud]);
    }

    public function show(Etudiant $idEtudiant)
    {
        $etudiant = Etudiant::find($idEtudiant);
        return response()->json(['status' => 'success', 'data' => $etudiant]);
    }

    public function update(Request $request,String $idEtudiant )
    {
        $etudiant = Etudiant::find($idEtudiant);

        $request->validate([
            'CIN' => 'required',
            'nom' => 'required',
            'prenom' => 'required',
            'dateNaissance' => 'required',
            'numTele' => 'required',
            'email' => 'required','email','regex:/@emsi\.ma$/i',
            'password'=>'required',
            'groupe_id' => 'required'

        ]);
        $etudiant->update([
            'CIN' => $request->CIN,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'dateNaissance' => $request->dateNaissance,
            'numTele' => $request->numTele,
            'email' => $request->email,
            'password' => bcrypt($request->input('password')),
            'groupe_id' => $request->groupe_id,

        ]);

        return response()->json([
            'message' => 'Etudiant mis à jour avec succès',
            'data' => $etudiant
        ]);
    }

    public function destroy(String $idEtudiant)
    {
        $etud = Etudiant::find($idEtudiant);

        if (!$etud) 
        {
            return response()->json(['message' => 'etudiant introuvable'], 404);
        }
       else
       {
        $etud->delete();
        return response()->json(['message' => 'Etudiant Supprimé'], 200);
       }
    }


    public function showModules($etudiantId)
    {
        // Récupérez l'étudiant par son ID ogroupe dyalo et les modules du groupe
        $etudiant = Etudiant::with(['groupe.modules', 'groupe'])->findOrFail($etudiantId);

        // verifivation si l'etudiant à un grp
        if ($etudiant->groupe) 
        {
            // Récupérez les modules associés au groupe de l'étudiant
            $modules = $etudiant->groupe->modules;
            return response()->json(['etudiant' => $etudiant, 'data' => $modules]);
        } 
        else 
        {
            return response()->json(['message' => 'L\'étudiant n\'est associé à aucun groupe'], 404);
        }
    }
}
