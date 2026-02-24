<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Permisos de usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            
            // Permisos de roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Permisos de permisos
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            
            // Dashboard (todos pueden ver)
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $gestorRolesRole = Role::firstOrCreate(['name' => 'gestor_roles', 'guard_name' => 'web']);

        // Asignar todos los permisos al admin
        $adminRole->syncPermissions(Permission::all());

        // Asignar permisos específicos al editor
        $editorRole->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'assign roles',
            'view roles',
            'view dashboard',
        ]);

        // Asignar permisos de gestión de roles al gestor_roles
        $gestorRolesRole->syncPermissions([
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view dashboard',
        ]);

        // Asignar permisos básicos al usuario
        $userRole->syncPermissions([
            'view dashboard',
        ]);

        // Asignar rol admin al primer usuario si existe
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasAnyRole(['admin', 'editor', 'user'])) {
            $firstUser->assignRole('admin');
            $this->command->info('Rol admin asignado al usuario: ' . $firstUser->email);
        }
    }
}
