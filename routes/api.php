<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\CertificatController;
use App\Http\Controllers\ProfexcelController;
use App\Http\Controllers\EtudiantExcelController;
use App\Http\Controllers\LoginEtudiantController;
use App\Http\Controllers\LoginProfController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\AnneeController;
use App\Http\Controllers\UtilisateurController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




//groupe routes
Route::get('groupe/index', [GroupeController::class, 'index']);
Route::post('groupe/create', [GroupeController::class, 'create']);
Route::put('groupe/update/{id}', [GroupeController::class, 'update']);
Route::delete('groupe/destroy/{id}', [GroupeController::class, 'destroy']);
Route::post('groupe/{groupeId}/{professeurIds}/assignerprofesseurs', [GroupeController::class, 'assignerProfesseurAuGroupe']);
Route::delete('group/{groupeId}/{professeurId}/removeprof', [GroupeController::class, 'removeProfesseurFromGroupe']);
Route::get('groupe/{id}/getEtudiants', [GroupeController::class, 'getEtudiants']);
Route::delete('group/{groupeId}/{etudiantId}/removeEtud', [GroupeController::class, 'removeEtudiantFromGroupe']);
Route::post('groupe/{groupeId}/associemoduleGrp/{moduleId}', [GroupeController::class, 'associateModuleGrp']);

//professeur routes:
Route::get('prof/{profId}/modules', [ProfesseurController::class, 'showModules']);
Route::post('prof/{profId}/associemodule/{moduleId}', [ProfesseurController::class, 'associateModule']);
Route::get('prof/{professeurId}/modules/{moduleId}/cours', [ProfesseurController::class, 'showCours']);
Route::post('prof/{professeurId}/assignCours', [ProfesseurController::class, 'assignCours']);

//Etudiant Routes:
Route::get('etudiant/index', [EtudiantController::class, 'index']);
Route::get('etudiant/show/{idEtudiant}', [EtudiantController::class, 'show']);
Route::post('etudiant/create', [EtudiantController::class, 'create']);
Route::put('etudiant/update/{idEtudiant}', [EtudiantController::class, 'update']);
Route::delete('etudiant/destroy/{idEtudiant}', [EtudiantController::class, 'destroy']);
Route::get('etudiant/{etudiantId}/modules', [EtudiantController::class, 'showModules']);

//Route Importer les fichiers Excels:
Route::post('/simple-excel/import', [ProfexcelController::class, 'import']);









/* Used */

//Login prof et etudiant
Route::post('signup', [UtilisateurController::class, 'signup']);
Route::post('login', [UtilisateurController::class, 'login']);
Route::post('loginEtud', [LoginEtudiantController::class, 'loginEtudiant']);
Route::post('loginProf', [LoginProfController::class, 'loginProf']);



//cours routes:
Route::get('cours/index', [CourController::class, 'index']);
Route::post('cours/create', [CourController::class, 'create']);
Route::put('cours/update/{id}', [CourController::class, 'update']);
Route::delete('cours/destroy/{id}', [CourController::class, 'destroy']);
Route::get('cours/{professeurId}/{groupeId}/getCoursEnseignesPourGroupe', [courController::class, 'getCoursEnseignesPourGroupe']);



//Route filiere:
Route::get('filiere/getFilieres', [FiliereController::class, 'getFilieres']);
Route::get('filiere/{filiereId}/groupes', [FiliereController::class, 'showGroupes']);



// Routes Année:
Route::get('annee/getAnnees', [AnneeController::class, 'getAnnees']);



//groupe routes
Route::get('groupe/{groupeId}/modules', [GroupeController::class, 'showModules']);
Route::get('groupe/{professeurId}/{filiereId}/getGroupesEnseignesPourFiliere', [GroupeController::class, 'getGroupesEnseignesPourFiliere']);



//professeur routes:
Route::get('prof/showOne/{id}', [ProfesseurController::class, 'showOne']);
Route::get('prof/showAll', [ProfesseurController::class, 'showAll']);
Route::post('prof/create', [ProfesseurController::class, 'create']);
Route::put('prof/update/{id}', [ProfesseurController::class, 'update']);
Route::delete('prof/destroy/{id}', [ProfesseurController::class, 'destroy']);
Route::get('prof/{profId}/getGrp_Prof', [ProfesseurController::class, 'getGrp_Prof']);



//Route module:
Route::get('module/getModules', [ModuleController::class, 'getModules']);
Route::get('module/getModule/{id}', [ModuleController::class, 'getModule']);
Route::get('module/{moduleId}/cours', [ModuleController::class, 'showCours']);



// Route certificat:
Route::get('/certificats/{courId}/{groupeId}', [CertificatController::class, 'showCertificatsForCoursAndGroupe']);
Route::post('/extractdate', [CertificatController::class, 'extractDateFromPDF']);



//Route Importer les fichiers Excels:
Route::post('/simple-excel/importEtudiant', [EtudiantExcelController::class, 'importEtudiants']);




//Etudiant Routes:
Route::get('etudiant/{etudiantId}/modules', [EtudiantController::class, 'showModules']);