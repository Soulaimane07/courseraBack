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
        
    
        $prof = Professeur::where('email', $infos['email'])->first();

        if ($prof && Hash::check($infos['password'], $prof->password)) {
            return response()->json(['status' => 'success', 'user' => $prof]);
        }

        return response()->json(['message' => 'Veuillez réessayer : email ou mot de passe invalide.'], 401);
    }
}
