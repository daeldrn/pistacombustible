<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'orders' => 89, // Ejemplo
            'sales' => 234, // Ejemplo
            'messages' => 45, // Ejemplo
        ];

        $users = User::latest()->take(10)->get();

        return view('dashboard', compact('stats', 'users'));
    }
}
