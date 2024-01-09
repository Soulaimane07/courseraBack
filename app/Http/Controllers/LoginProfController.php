<?php

namespace App\Http\Controllers;

use App\Models\Professeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class LoginProfController extends Controller
{
    public function loginProf(Request $request)
    {
        $infos = $request->only('email', 'password');
        
    
        $etudiant = Professeur::where('email', $infos['email'])->first();

        if ($etudiant && Hash::check($infos['password'], $etudiant->password)) {
            return response()->json(['message' => 'Connexion réussie!']);
        }

        return response()->json(['message' => 'Veuillez réessayer : email ou mot de passe invalide.'], 401);
    }
}
