<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Etudiant;
use Illuminate\Support\Facades\Hash;

class LoginEtudiantController extends Controller
{
  
    public function loginEtudiant(Request $request)
    {
        $var = $request->only('email', 'password');
        
        $etudiant = Etudiant::where('email', $var['email'])->first();

        if ($etudiant && Hash::check($var['password'], $etudiant->password)) {
            return response()->json(['status' => 'success', 'user' => $etudiant]);
        }

        return response()->json(['message' => ' Veuillez réessayer : email ou mot de passe invalide.'], 401);
        
    }
}


