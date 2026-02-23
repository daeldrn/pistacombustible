<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get dashboard statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        // Cache por 5 minutos (300 segundos)
        $stats = Cache::remember('dashboard.stats', 300, function () {
            return [
                'users' => $this->userRepository->count(),
                'active_users' => $this->userRepository->countActive(),
                'inactive_users' => $this->userRepository->countInactive(),
                'orders' => 89, // Ejemplo - reemplazar con datos reales
                'sales' => 234, // Ejemplo - reemplazar con datos reales
                'messages' => 45, // Ejemplo - reemplazar con datos reales
            ];
        });

        return ApiResponse::success($stats, 'Estadísticas obtenidas exitosamente');
    }

    /**
     * Get recent users
     *
     * @return JsonResponse
     */
    public function recentUsers(): JsonResponse
    {
        // Cache por 2 minutos (120 segundos)
        $users = Cache::remember('dashboard.recent_users', 120, function () {
            return $this->userRepository->getRecent(10);
        });

        return ApiResponse::success(
            UserResource::collection($users),
            'Usuarios recientes obtenidos exitosamente'
        );
    }

    /**
     * Get all dashboard data
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Cache por 5 minutos
        $dashboardData = Cache::remember('dashboard.all', 300, function () {
            $stats = [
                'users' => $this->userRepository->count(),
                'active_users' => $this->userRepository->countActive(),
                'inactive_users' => $this->userRepository->countInactive(),
                'orders' => 89,
                'sales' => 234,
                'messages' => 45,
            ];

            $recentUsers = $this->userRepository->getRecent(10);

            return [
                'stats' => $stats,
                'recent_users' => UserResource::collection($recentUsers),
            ];
        });

        return ApiResponse::success($dashboardData, 'Dashboard obtenido exitosamente');
    }
}
