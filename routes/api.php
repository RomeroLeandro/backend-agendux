<?php

use App\Http\Controllers\PlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserPlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('plans', PlanController::class)->only([
    'index', 'show'
]);

Route::middleware('auth:sanctum')->group(function () {
    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ruta para obtener los datos del usuario autenticado.
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de administración de planes (solo para admins)
    Route::apiResource('plans', PlanController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::put('/user/plan', [UserPlanController::class, 'update']);

    Route::apiResource('users', UserController::class);

    Route::put('/profile', [ProfileController::class, 'update']);
});

