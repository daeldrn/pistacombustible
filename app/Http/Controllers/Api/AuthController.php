<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login user and return token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Rate limiting
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        $result = $this->authService->attemptLogin($credentials, false);

        if ($result['success']) {
            $user = Auth::user();
            
            // Revocar tokens anteriores (opcional)
            // $user->tokens()->delete();
            
            // Crear nuevo token
            $token = $user->createToken('auth-token')->plainTextToken;
            
            RateLimiter::clear($request->throttleKey());

            return ApiResponse::success([
                'token' => $token,
                'user' => UserResource::make($user),
            ], 'Login exitoso');
        }

        RateLimiter::hit($request->throttleKey());

        return ApiResponse::unauthorized($result['message']);
    }

    /**
     * Logout user and revoke token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Revocar el token actual
        $request->user()->currentAccessToken()->delete();
        
        return ApiResponse::success(null, 'Logout exitoso');
    }

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success(
            UserResource::make($request->user()),
            'Usuario autenticado'
        );
    }
}
