<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test {role=gestor_roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear usuario de prueba con rol específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = $this->argument('role');
        
        $email = "test_{$role}@example.com";
        
        // Verificar si el usuario ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("El usuario {$email} ya existe.");
            return 1;
        }
        
        // Crear usuario
        $user = User::create([
            'name' => ucfirst(str_replace('_', ' ', $role)),
            'email' => $email,
            'password' => Hash::make('password'),
            'activo' => true,
        ]);
        
        // Asignar rol
        try {
            $user->assignRole($role);
            $this->info("Usuario creado exitosamente:");
            $this->line("Email: {$email}");
            $this->line("Password: password");
            $this->line("Rol: {$role}");
            
            // Mostrar permisos
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            $this->line("Permisos: " . implode(', ', $permissions));
            
            return 0;
        } catch (\Exception $e) {
            $user->delete();
            $this->error("Error al asignar rol: " . $e->getMessage());
            return 1;
        }
    }
}
