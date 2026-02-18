<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_users_can_be_created()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/users', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'activo' => true,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_users_can_be_updated()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        // El usuario se actualiza a sí mismo (permitido por la policy)
        $response = $this->actingAs($user)->put("/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'old@example.com',
            'activo' => true,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    public function test_user_cannot_update_other_users()
    {
        $authUser = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($authUser)->put("/users/{$targetUser->id}", [
            'name' => 'New Name',
            'email' => 'old@example.com',
            'activo' => true,
        ]);

        // Debería recibir 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_users_can_be_deleted()
    {
        $authUser = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($authUser)->delete("/users/{$targetUser->id}");

        $response->assertRedirect('/users');
        $this->assertSoftDeleted('users', [
            'id' => $targetUser->id,
        ]);
    }

    public function test_user_cannot_delete_themselves()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete("/users/{$user->id}");

        $response->assertStatus(403);
    }

    public function test_validation_errors_are_returned_for_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/users', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}
