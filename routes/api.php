<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MemberController;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api/v1 (set in bootstrap/app.php).
| Sanctum's stateful middleware is applied globally via bootstrap/app.php.
|
*/

// ─── Public (guest) ──────────────────────────────────────────────────────────

Route::post('/login', [AuthController::class, 'login']);

// ─── Authenticated ───────────────────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Members — any authenticated user can list / view
    Route::get('/members', [MemberController::class, 'index']);
    Route::get('/members/{member}', [MemberController::class, 'show']);

    // Members — admin only
    Route::middleware('role:admin')->group(function () {
        Route::post('/members', [MemberController::class, 'store']);
        Route::put('/members/{member}', [MemberController::class, 'update']);
        Route::delete('/members/{member}', [MemberController::class, 'destroy']);
    });

    // Admin-only routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Future: settings, reports, etc.
    });

    // Committee + Admin routes
    Route::middleware('role:admin,committee')->prefix('manage')->group(function () {
        // Future: event management, attendance, etc.
    });
});
