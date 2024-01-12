<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Hash;

class EtudiantExcelController extends Controller
{
    public function importEtudiants(Request $request)
    {
        // Validation de l'extension
        $this->validate($request, [
            'fichier' => 'bail|required|file|mimes:xlsx'
        ]);

        // Déplacer le fichier uploadé vers le dossier "public" pour le lire
        $fichier = $request->fichier->move(public_path(), $request->fichier->hashName());
        $reader = SimpleExcelReader::create($fichier);
        // On récupère le contenu (les lignes) du fichier
        $rows = $reader->getRows();
        // Hacher les mots de passe et insérer toutes les lignes dans la base de données
        $hashedRows = $rows->map(function ($row) {
            $row['password'] = Hash::make($row['password']);
            return $row;
        });
        // On insère toutes les lignes dans la base de données
        $status = Etudiant::insert($hashedRows->toArray());

        // Si toutes les lignes sont insérées
        if ($status) {
            $reader->close(); 
            return response()->json(['data' => 'Data imported successfully']);
        } else {
            abort(500);
        }
    }
}
