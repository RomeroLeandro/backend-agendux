<?php

use App\Http\Controllers\PlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    UserController,
    ProfileController,
    UserPlanController,
};
use App\Http\Controllers\ServiceController;


Route::apiResource('plans', PlanController::class)->only(['index', 'show']);

// Rutas públicas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);
});


/*
|--------------------------------------------------------------------------
| Rutas de API Protegidas (Requieren login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Rutas protegidas de autenticación y perfil de usuario
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', function (Request $request) {
            return $request->user();
        });
    });

    // Rutas para que el usuario gestione su propio perfil y plan
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::put('/user/plan', [UserPlanController::class, 'update']);
    Route::apiResource('services', ServiceController::class);

    /*
    |--------------------------------------------------------------------------
    | Rutas de Administración (Requieren rol de 'admin')
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        // Rutas de administración de planes (crear, editar, borrar)
        Route::apiResource('plans', PlanController::class)->except(['index', 'show']);
        // Rutas de administración de usuarios (CRUD completo)
        Route::apiResource('users', UserController::class);
    });
});

