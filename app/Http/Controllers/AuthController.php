<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
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

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        // Rate limiting
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $result = $this->authService->attemptLogin($credentials, $remember);

        if ($result['success']) {
            $request->session()->regenerate();
            RateLimiter::clear($request->throttleKey());
            
            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($request->throttleKey());

        return back()->withErrors([
            'email' => $result['message'],
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
