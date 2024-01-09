<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UtilisateurController extends Controller
{
    public function signup(Request $request){
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/@emsi\.ma$/i', 
                'unique:users',
            ],
            'password' => 'required',
            'role' => 'required|in:responsable,etudiant,prof',
            'nom' => 'required',
            'prenom' => 'required',
        ]);

        $user = User::create([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
        ]);

        return $user;

    }

    public function login(Request $request){
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/@emsi\.ma$/i', 
            ],
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // return Auth::user();
            return response()->json(['status' => 'success', 'user' => Auth::user()]);
        } else {
            return back()->withErrors([
                'email.required' => 'Le champ email est obligatoire.',
                'email.email' => "L'adresse email doit être valide.",
                'email.regex' => "L'adresse email doit se terminer par '@emsi.ma'.",
                'password.required' => 'Le champ mot de passe est obligatoire.',

            ])->onlyInput('email', 'password');
        }
    }

    public function logout(Request $request){
        if(Auth::logout()){
            return true;
        } else {
            return false;
        }
    }
}