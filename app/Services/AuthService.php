<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AuthService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param array $credentials
     * @param bool $remember
     * @return array
     */
    public function attemptLogin(array $credentials, bool $remember = false): array
    {
        if (Auth::attempt($credentials, $remember)) {
            // Verificar si el usuario está activo
            if (!Auth::user()->activo) {
                Auth::logout();
                
                Log::warning('Intento de login con cuenta inactiva', ['email' => $credentials['email']]);
                
                return [
                    'success' => false,
                    'message' => 'Su cuenta está inactiva. Contacte al administrador.',
                ];
            }

            // Limpiar intentos de rate limiting
            RateLimiter::clear($this->throttleKey($credentials['email']));

            Log::info('Login exitoso', ['user_id' => Auth::id(), 'email' => Auth::user()->email]);

            return [
                'success' => true,
                'user' => Auth::user(),
            ];
        }

        Log::warning('Intento de login fallido', ['email' => $credentials['email']]);

        return [
            'success' => false,
            'message' => 'Las credenciales no coinciden con nuestros registros.',
        ];
    }

    /**
     * Log out the user.
     *
     * @return void
     */
    public function logout(): void
    {
        $userId = Auth::id();
        Auth::logout();
        
        Log::info('Logout exitoso', ['user_id' => $userId]);
    }

    /**
     * Get the rate limiting throttle key.
     *
     * @param string $email
     * @return string
     */
    protected function throttleKey(string $email): string
    {
        return strtolower($email) . '|' . request()->ip();
    }
}
