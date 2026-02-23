<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    protected $authService;

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

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'activo' => $user->activo,
                ]
            ], 200);
        }

        RateLimiter::hit($request->throttleKey());

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 401);
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
        
        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ], 200);
    }

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ], 200);
    }
}
