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
            $certificat->note = 20;
            $certificat->save();

            $cours = Cour::where('dateFin', '>=', $formattedDate) ->where('deadline_control', '>=', $formattedDate)->first();
            if ($cours) {
                $dateFinCours = $cours->dateFin;
                $deadlineControl = $cours->deadline_control;
                if ($formattedDate > $deadlineControl) {
                    // La date extraite est après la deadline de contrôle, la note est 0
                    $newNote = 0;
                }
                elseif ($formattedDate <= $dateFinCours) 
                {    
                    $newNote = 20;

                    // Mettre à jour la note du certificat
                    // $certificat = Certificat::updateOrCreate(
                    //     ['date_obtention' => $formattedDate, 'cour_id' => $cours->id],
                    //     ['note' => $newNote]
                    // );
                }
                elseif($formattedDate > $dateFinCours && $formattedDate <= $deadlineControl) {
                        // Calculer le nombre de jours de retard
                        $daysLate = Carbon::parse($formattedDate)->diffInDays(Carbon::parse($dateFinCours));
                
                        // Appliquer une pénalité de -2 par jour de retard
                        $newNote = max(20 - ($daysLate * 2), 0);
                
                        // Mettre à jour la note du certificat
                        $certificat = Certificat::updateOrCreate(
                            ['date_obtention' => $formattedDate, 'cour_id' => $cours->id],
                            ['note' => $newNote]
                        );
                }
        else {
            // Aucun cours correspondant trouvé
        }

                return response()->json(['date' => $formattedDate, 'message' => 'Certificat ajouté et note mise à jour']);
            } else {
                return response()->json(['error' => 'Date not found in PDF']);
            }
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
}

