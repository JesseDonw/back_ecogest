<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LocalisationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ici, vous pouvez enregistrer les routes API de votre application. Ces routes
| sont chargées par le RouteServiceProvider et seront automatiquement préfixées
| avec "/api". Assurez-vous de bien configurer les contrôleurs associés.
|
*/

/* 🔒 Route protégée pour récupérer l'utilisateur authentifié */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* ==============================
 * 📌 AUTHENTIFICATION & INSCRIPTION
 * ============================== */
Route::post('register', [AuthController::class, 'register']);          // ✅ Inscription d'un agent
Route::post('registercli', [AuthController::class, 'registercli']);    // ✅ Inscription d'un client
Route::post('registeradmin', [AuthController::class, 'registeradmin']);// ✅ Inscription d'un admin

Route::post('login', [AuthController::class, 'login']);                // ✅ Connexion d'un agent
Route::post('logincli', [AuthController::class, 'logincli']);          // ✅ Connexion d'un client
Route::post('loginadmin', [AuthController::class, 'loginadmin']);      // ✅ Connexion d'un admin

Route::post('/logout', [AuthController::class, 'logout']);             // ✅ Déconnexion


/* ==============================
 * 📌 GESTION DES AGENTS, CLIENTS & ADMINS
 * ============================== */
Route::get('/agents', [AuthController::class, 'getAllAgents']);  // ✅ Récupérer tous les agents
Route::get('/agent/{id}', [AuthController::class, 'getAgent']);  // ✅ Récupérer un agent spécifique
Route::get('/clients', [AuthController::class, 'getAllClients']); // ✅ Récupérer tous les clients
Route::get('/client/{id}', [AuthController::class, 'getClient']);// ✅ Récupérer un client spécifique
Route::get('/admin/{id}', [AuthController::class, 'getAdmin']);  // ✅ Récupérer un administrateur spécifique

Route::put('/agent/{id}', [AuthController::class, 'updateAgent']); // ✅ Mettre à jour un Agent
Route::delete('/agent/{id}', [AuthController::class, 'deleteAgent']); // ✅ Supprimer un agent spécifique
Route::get('/admins', [AuthController::class, 'getAllAdmin']);
Route::delete('/admin/{id}', [AuthController::class, 'deleteAdmin']); // ✅ Supprimer un agent spécifique


/* ==============================
 * 📌 LOCALISATION DES CLIENTS
 * ============================== */
Route::post('/storelocation', [LocalisationController::class, 'storelocation']); // ✅ Ajouter la localisation d'un client

Route::post('/localisations', [LocalisationController::class, 'storeLocation']); // ✅ Ajouter une localisation
Route::get('/localisations', [LocalisationController::class, 'getAll']);         // ✅ Récupérer toutes les localisations
Route::get('/localisations/{id}', [LocalisationController::class, 'getById']);   // ✅ Récupérer une localisation spécifique
Route::put('/localisations/{id}', [LocalisationController::class, 'updateLocation']); // ✅ Mettre à jour une localisation
Route::delete('/localisations/{id}', [LocalisationController::class, 'deleteLocation']); // ✅ Supprimer une localisation



/* ==============================
 * 📌 GESTION DES MESSAGES & DISCUSSIONS
 * ============================== */
Route::post('/start-conversation', [ChatController::class, 'startConversation']); // ✅ Démarrer une conversation
Route::post('/send-message', [ChatController::class, 'sendMessage']);             // ✅ Envoyer un message
Route::get('/messages/{conversationId}', [ChatController::class, 'getMessages']); // ✅ Récupérer les messages d'une conversation
Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages']); // Récupérer les messages d'une conversation spécifique


/* ==============================
 * 📌 GESTION DES TÂCHES
 * ============================== */
Route::get('/taches', [TaskController::class, 'getAll']);         // ✅ Récupérer toutes les tâches
Route::post('/taches', [TaskController::class, 'store']);        // ✅ Ajouter une tâche
Route::get('/taches/{id}', [TaskController::class, 'show']);     // ✅ Récupérer une tâche spécifique
Route::put('/taches/{id}', [TaskController::class, 'updateTache']);   // ✅ Mettre à jour une tâche
Route::delete('/taches/{id}', [TaskController::class, 'deleteTache']);// ✅ Supprimer une tâche
Route::patch('/taches/{id}/statut', [TaskController::class, 'updateStatut']); // ✅ Mettre à jour uniquement le statut d'une tâche
// Récupérer les tâches en attente
Route::get('/taches/en-attente', [TaskController::class, 'getPendingTasks']);
Route::get('/taches/statut/{status}', [TaskController::class, 'getTasksByStatus']);  // Récupérer les tâches par statut
Route::get('/taches/tri/localisation', [TaskController::class, 'getTasksSortedByLocation']);  // Trier les tâches par localisation
Route::post('/taches/plus-proche', [TaskController::class, 'getNearestTasks']);  // Récupérer les tâches proches
Route::put('/taches/{id}/validate', [TaskController::class, 'validateTache']);  // Valider une tâche
// Route protégée par Sanctum pour récupérer les tâches accomplies par l'agent
Route::get('/taches/done', [TaskController::class, 'getDoneTasks']);
// Nombre de tache
Route::get('/tasks/count/sorted-by-location', [TaskController::class, 'getTasksCountSortedByLocation']);






Route::middleware('auth:sanctum')->post('/send-message', [MessageController::class, 'sendMessage']);

Route::middleware('auth:sanctum')->get('/fetch-messages', [MessageController::class, 'getMessages']);

Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json(['user' => Auth::user()]);
});


