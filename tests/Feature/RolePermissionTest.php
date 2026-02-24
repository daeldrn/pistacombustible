<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Crear permisos
        Permission::firstOrCreate(['name' => 'users.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.delete', 'guard_name' => 'web']);
        
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        
        // Asignar permisos a roles
        $adminRole->syncPermissions(Permission::all());
        $editorRole->syncPermissions(['users.view', 'users.create', 'users.edit']);
        $userRole->syncPermissions(['users.view']);
    }

    public function test_admin_can_access_roles_endpoint()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/pista/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'guard_name', 'permissions']
                ]
            ]);
    }

    public function test_non_admin_cannot_access_roles_endpoint()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/pista/roles');

        $response->assertStatus(403);
    }

    public function test_can_assign_role_to_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/pista/users/{$user->id}/roles", [
                'roles' => ['editor']
            ]);

        $response->assertStatus(200);
        $this->assertTrue($user->fresh()->hasRole('editor'));
    }

    public function test_can_assign_permissions_to_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/pista/users/{$user->id}/permissions", [
                'permissions' => ['users.create', 'users.edit']
            ]);

        $response->assertStatus(200);
        $this->assertTrue($user->fresh()->hasPermissionTo('users.create'));
        $this->assertTrue($user->fresh()->hasPermissionTo('users.edit'));
    }

    public function test_can_create_new_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/pista/roles', [
                'name' => 'moderator',
                'permissions' => ['users.view', 'users.edit']
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'moderator');
        
        $this->assertDatabaseHas('roles', ['name' => 'moderator']);
    }

    public function test_can_create_new_permission()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/pista/permissions', [
                'name' => 'posts.create'
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'posts.create');
        
        $this->assertDatabaseHas('permissions', ['name' => 'posts.create']);
    }

    public function test_user_with_role_has_correct_permissions()
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $this->assertTrue($editor->hasPermissionTo('users.view'));
        $this->assertTrue($editor->hasPermissionTo('users.create'));
        $this->assertTrue($editor->hasPermissionTo('users.edit'));
        $this->assertFalse($editor->hasPermissionTo('users.delete'));
    }

    public function test_can_remove_role_from_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();
        $user->assignRole('editor');

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/pista/users/{$user->id}/roles", [
                'role' => 'editor'
            ]);

        $response->assertStatus(200);
        $this->assertFalse($user->fresh()->hasRole('editor'));
    }

    public function test_can_revoke_permission_from_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();
        $user->givePermissionTo('users.create');

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/pista/users/{$user->id}/permissions", [
                'permission' => 'users.create'
            ]);

        $response->assertStatus(200);
        $this->assertFalse($user->fresh()->hasPermissionTo('users.create'));
    }

    public function test_cannot_delete_protected_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $adminRole = Role::findByName('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/pista/roles/{$adminRole->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
    }

    public function test_user_permissions_include_role_and_direct_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $user->givePermissionTo('users.delete');

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/pista/users/{$user->id}/permissions");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'roles',
                    'direct_permissions',
                    'all_permissions'
                ]
            ]);

        $allPermissions = $response->json('data.all_permissions');
        $permissionNames = array_column($allPermissions, 'name');
        
        $this->assertContains('users.view', $permissionNames);
        $this->assertContains('users.create', $permissionNames);
        $this->assertContains('users.edit', $permissionNames);
        $this->assertContains('users.delete', $permissionNames);
    }
}
