<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserRoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Dashboard - Todos los usuarios autenticados pueden ver el dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-users', [DashboardController::class, 'recentUsers']);
    
    // Users - Protegido con permisos
    Route::middleware(['permission:view users'])->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);
    });
    
    Route::middleware(['permission:create users'])->group(function () {
        Route::post('users', [UserController::class, 'store']);
    });
    
    Route::middleware(['permission:edit users'])->group(function () {
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::patch('users/{user}', [UserController::class, 'update']);
    });
    
    Route::middleware(['permission:delete users'])->group(function () {
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });
    
    // User Roles & Permissions Management - Solo admin
    Route::middleware(['role:admin'])->prefix('users/{userId}')->group(function () {
        Route::post('/roles', [UserRoleController::class, 'assignRoles']);
        Route::delete('/roles', [UserRoleController::class, 'removeRole']);
        Route::post('/permissions', [UserRoleController::class, 'assignPermissions']);
        Route::delete('/permissions', [UserRoleController::class, 'revokePermission']);
        Route::get('/permissions', [UserRoleController::class, 'getUserPermissions']);
    });
    
    // Listar roles y permisos - Protegido con permisos
    Route::middleware(['permission:view roles'])->group(function () {
        Route::get('roles', [RoleController::class, 'index']);
    });
    
    Route::middleware(['permission:view permissions'])->group(function () {
        Route::get('permissions', [PermissionController::class, 'index']);
    });
    
    // Roles & Permissions Management (solo admin puede crear/editar/eliminar)
    Route::middleware(['role:admin'])->group(function () {
        Route::post('roles', [RoleController::class, 'store']);
        Route::get('roles/{role}', [RoleController::class, 'show']);
        Route::put('roles/{role}', [RoleController::class, 'update']);
        Route::delete('roles/{role}', [RoleController::class, 'destroy']);
        
        Route::post('permissions', [PermissionController::class, 'store']);
        Route::get('permissions/{permission}', [PermissionController::class, 'show']);
        Route::put('permissions/{permission}', [PermissionController::class, 'update']);
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy']);
    });
});
