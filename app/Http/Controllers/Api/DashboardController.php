<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'users' => User::count(),
            'active_users' => User::where('activo', true)->count(),
            'inactive_users' => User::where('activo', false)->count(),
            'orders' => 89, // Ejemplo - reemplazar con datos reales
            'sales' => 234, // Ejemplo - reemplazar con datos reales
            'messages' => 45, // Ejemplo - reemplazar con datos reales
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ], 200);
    }

    /**
     * Get recent users
     *
     * @return JsonResponse
     */
    public function recentUsers(): JsonResponse
    {
        $users = User::latest()->take(10)->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    /**
     * Get all dashboard data
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $stats = [
            'users' => User::count(),
            'active_users' => User::where('activo', true)->count(),
            'inactive_users' => User::where('activo', false)->count(),
            'orders' => 89,
            'sales' => 234,
            'messages' => 45,
        ];

        $recentUsers = User::latest()->take(10)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_users' => $recentUsers
            ]
        ], 200);
    }
}
