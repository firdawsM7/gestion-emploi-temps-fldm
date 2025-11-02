<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmploiController;
use App\Models\Groupe;

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

// Routes API publiques pour les groupes (sans authentification)
Route::get('/groupes', function(Request $request) {
    $filiereId = $request->query('filiere_id');
    $semestreId = $request->query('semestre_id');
    
    if (!$filiereId) {
        return response()->json(['error' => 'filiere_id est requis'], 400);
    }
    
    $query = Groupe::where('id_filiere', $filiereId);
    
    if ($semestreId) {
        $query->where('id_semestre', $semestreId);
    }
    
    $groupes = $query->get();
    
    return response()->json($groupes);
});

// Route pour charger les groupes par filière et semestre
Route::get('/groupes-by-filiere-semestre', [EmploiController::class, 'getGroupesByFiliereAndSemestre']);
Route::get('/groupes', [EmploiController::class, 'getGroupesByFiliereAndSemestre']);
Route::get('/salles/disponibilite', [EmploiController::class, 'apiDisponibilite']);
// Routes API protégées (avec authentification)
Route::middleware(['auth:sanctum'])->group(function () {
    // Salles disponibles
    Route::get('/salles-disponibles', [EmploiController::class, 'apiDisponibilite']);
    
    // Notifications count
    Route::get('/notifications/count', function() {
        $count = Auth::user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    });
    
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});