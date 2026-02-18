<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'activo' => true,
        ]);

        // Crear usuario de prueba
        User::create([
            'name' => 'Usuario Test',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'activo' => true,
        ]);

        // Crear usuario inactivo
        User::create([
            'name' => 'Usuario Inactivo',
            'email' => 'inactive@example.com',
            'password' => Hash::make('password'),
            'activo' => false,
        ]);

        // Crear usuarios adicionales usando factory
        User::factory(10)->create();
    }
}
