<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\CoachingSessionController;
use App\Http\Middleware\EnsureTokenIsValid;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:api', EnsureTokenIsValid::class, 'role:coach|client'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'throttle:api', EnsureTokenIsValid::class, 'role:coach'])->group(function () {
    Route::prefix('client')->group(function () {
        Route::get('get-profile', [ClientController::class, 'getClientProfile']);
        Route::put('update-profile', [ClientController::class, 'updateClientProfile']);
        // Route::post('create-profile', [ClientController::class, 'createClientProfile']);
        // Route::delete('delete-profile', [ClientController::class, 'deleteClientProfile']);
    });

    Route::prefix('coach')->group(function () {
        Route::get('analytics', [CoachController::class, 'getCoachAnalytics']);
    });

    Route::prefix('coaching-sessions')->group(function () {
        Route::post('create-session', [CoachingSessionController::class, 'createCoachingSession']);
        Route::get('get-all-session', [CoachingSessionController::class, 'getAllCoachingSessions']);
        Route::get('get-session', [CoachingSessionController::class, 'getCoachingSession']);
        Route::put('update-session', [CoachingSessionController::class, 'updateCoachingSession']);
        Route::delete('delete-session', [CoachingSessionController::class, 'deleteCoachingSession']);

        Route::get('get-uncompleted', [CoachingSessionController::class, 'getUncompletedCoachingSessions']);
        Route::get('get-completed', [CoachingSessionController::class, 'getCompletedCoachingSessions']);
    });
});

Route::middleware(['auth:sanctum', 'throttle:api', EnsureTokenIsValid::class, 'role:client'])->group(function () {
    Route::prefix('coaching-sessions')->group(function () {
        Route::post('mark-completed', [CoachingSessionController::class, 'markCoachingSessionCompleted']);
    });
});

// - CRUD operations for managing client profiles and coaching sessions.

// Fetching analytics data for coaches, including:
//     - Total sessions conducted.
//     - Client progress (e.g., percentage of completed sessions).

// Include logic to handle client sessions:
// - Clients can fetch their uncompleted sessions.
// - Clients can mark a session as completed using their token.
