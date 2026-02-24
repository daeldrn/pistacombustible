<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SetupPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:setup {--fresh : Eliminar permisos existentes y crear nuevos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar roles y permisos del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Configurando roles y permisos...');
        $this->newLine();

        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->info('✓ Caché de permisos limpiado');

        if ($this->option('fresh')) {
            $this->warn('⚠️  Eliminando permisos y roles existentes...');
            Permission::query()->delete();
            Role::query()->delete();
            $this->info('✓ Permisos y roles eliminados');
        }

        // Crear permisos
        $this->info('📝 Creando permisos...');
        $permissions = $this->createPermissions();
        $this->info("✓ {$permissions->count()} permisos creados/actualizados");

        // Crear roles
        $this->info('👥 Creando roles...');
        $this->createRoles();
        $this->info('✓ Roles creados/actualizados');

        // Asignar rol admin al primer usuario
        $this->assignAdminToFirstUser();

        $this->newLine();
        $this->info('✅ Configuración completada exitosamente!');
        $this->newLine();

        // Mostrar resumen
        $this->showSummary();

        return Command::SUCCESS;
    }

    private function createPermissions()
    {
        $permissions = [
            // Permisos de usuarios
            'view users' => 'Ver lista de usuarios',
            'create users' => 'Crear nuevos usuarios',
            'edit users' => 'Editar usuarios existentes',
            'delete users' => 'Eliminar usuarios',
            
            // Permisos de roles
            'view roles' => 'Ver lista de roles',
            'create roles' => 'Crear nuevos roles',
            'edit roles' => 'Editar roles existentes',
            'delete roles' => 'Eliminar roles',
            
            // Permisos de permisos
            'view permissions' => 'Ver lista de permisos',
            'create permissions' => 'Crear nuevos permisos',
            'edit permissions' => 'Editar permisos existentes',
            'delete permissions' => 'Eliminar permisos',
            
            // Dashboard
            'view dashboard' => 'Ver dashboard',
        ];

        $createdPermissions = collect();

        foreach ($permissions as $name => $description) {
            $permission = Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web']
            );
            $createdPermissions->push($permission);
        }

        return $createdPermissions;
    }

    private function createRoles()
    {
        // Rol Admin - Todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());
        $this->line("  → admin: " . $adminRole->permissions->count() . " permisos");

        // Rol Editor - Permisos limitados
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'view roles',
            'view dashboard',
        ]);
        $this->line("  → editor: " . $editorRole->permissions->count() . " permisos");

        // Rol User - Solo dashboard
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view dashboard',
        ]);
        $this->line("  → user: " . $userRole->permissions->count() . " permisos");
    }

    private function assignAdminToFirstUser()
    {
        $firstUser = User::first();
        
        if (!$firstUser) {
            $this->warn('⚠️  No hay usuarios en el sistema');
            return;
        }

        if (!$firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
            $this->info("✓ Rol admin asignado a: {$firstUser->email}");
        } else {
            $this->line("  → {$firstUser->email} ya tiene rol admin");
        }
    }

    private function showSummary()
    {
        $this->table(
            ['Rol', 'Permisos', 'Usuarios'],
            Role::with('permissions', 'users')->get()->map(function ($role) {
                return [
                    $role->name,
                    $role->permissions->count(),
                    $role->users->count(),
                ];
            })
        );

        $this->newLine();
        $this->info('💡 Comandos útiles:');
        $this->line('  php artisan permissions:setup --fresh  # Recrear desde cero');
        $this->line('  php artisan tinker                     # Verificar permisos');
    }
}
