<?php

namespace App\Http\Controllers;

use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use App\Models\Certificat;
use App\Models\Cour;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CertificatController extends Controller
{
    public function extractDateFromPDF(Request $request)
    {
        $file = $request->file('pdf');
        $filename = uniqid('pdf_') . '.' . $file->getClientOriginalExtension();

        // Déplacer le fichier vers le répertoire de stockage
        $file->storeAs('public/pdf', $filename);
        $file->move(public_path('certificats'), $filename);

        // Obtenez le nom du fichier d'origine
        $originalFilename = $file->getClientOriginalName();

        // Construire le chemin complet du fichier dans le répertoire de stockage
        $filePath = 'public/pdf/' . $filename;
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(Storage::path($filePath));

        $text = $pdf->getText();
        $firstLine = explode("\n", $text)[0];

        $firstLine = preg_replace('/\s+/', ' ', $firstLine);
        $firstLine = preg_replace('/(\d+)\s+(\d+)/', '$1$2', $firstLine);
        $firstLine = preg_replace('/(\d+)\s+(\d+)/', '$1$2', $firstLine);

        $carbonDate = Carbon::parse($firstLine);
        $formattedDate = $carbonDate->format('Y-m-d');

        if (!empty($formattedDate)) {
            // Enregistrement de la certification dans la base de données
            $certificat = new Certificat();
            $certificat->titre = $request->input('titre');
            $certificat->etudiant_id = $request->input('etudiant_id');
            $certificat->cour_id = $request->input('cour_id');
            $certificat->pdf = 'certificats/' . $filename;
            $certificat->date_obtention = $formattedDate;
                // recupéerer les infors du cours
                $cours = Cour::find($request->input('cour_id'));

                if ($cours) 
                {
                    $dateFinCours = $cours->dateFin;
                    $deadlineControl = $cours->deadline_control;

                    // Calculer la note en fonction de la date extraite
                    if ($formattedDate <= $dateFinCours) {
                        // La date extraite est avant ou égale à la date de fin du cours
                        $certificat->note = 20;
                    } elseif ($formattedDate <= $deadlineControl) {
                        // La date extraite est après la date de fin du cours mais avant la deadline de contrôle
                        $daysLate = $carbonDate->diffInDays(Carbon::parse($dateFinCours));
                        $certificat->note = max(0, 20 - ($daysLate * 2));
                    } else {
                        // La date extraite dépasse la deadline de contrôle
                        $certificat->note = 0;
                    }

                    // Enregistrement de la certification dans la base de données
                    $certificat->save();

                    return response()->json(['date' => $formattedDate, 'message' => 'Certificat ajouté et note mise à jour']);
                } 
                else 
                {
                    return response()->json(['error' => 'Aucun cours correspondant trouvé']);
                }
        } 
        else 
        {
            return response()->json(['error' => 'Aucune date trouvé dans le PDF']);
        }
    }

    public function showCertificatsForCoursAndGroupe($courId, $groupeId)
    {
        $certificats = Certificat::join('etudiants', 'certificats.etudiant_id', '=', 'etudiants.id')
            ->join('cours', 'certificats.cour_id', '=', 'cours.id')
            ->join('groupes', 'etudiants.groupe_id', '=', 'groupes.id')
            ->where('certificats.cour_id', $courId)
            ->where('etudiants.groupe_id', $groupeId)
            ->select('certificats.*')
            ->get();

        return response()->json(['data' => $certificats]);
    }

    public function getCertificatsEtCoursManquants($etudiantId, $moduleId)
    {
        // Récupération de tous les cours du module
        $coursModule = DB::table('cours')
            ->where('module_id', $moduleId)
            ->pluck('id');
        // Récupération des certificats de l'étudiant pour les cours du module donnée:
        $certificatsEtudiant = DB::table('certificats')
            ->whereIn('cour_id', $coursModule)
            ->where('etudiant_id', $etudiantId)
            ->pluck('cour_id');
        // jbna le nombre total dyal les certificats pour l'étudiant dans le module
        $nombreCertificats = count($certificatsEtudiant);

        // Récupération des cours limakhdach fihum certificat
        $coursManquants = DB::table('cours')
            ->where('module_id', $moduleId)
            ->whereNotIn('id', $certificatsEtudiant)
            ->get();
        return response()->json([
            'data' => $nombreCertificats,
            'coursManquants' => $coursManquants,
        ]);
    }
}

