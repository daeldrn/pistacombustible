<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'activo' => $data['activo'] ?? true,
            ]);

            Log::info('Usuario creado exitosamente', ['user_id' => $user->id, 'email' => $user->email]);

            // Disparar evento
            event(new \App\Events\UserCreated($user));

            return $user;
        } catch (\Exception $e) {
            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing user.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        try {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->activo = $data['activo'] ?? false;

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            Log::info('Usuario actualizado exitosamente', ['user_id' => $user->id]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Delete a user (soft delete).
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        try {
            $userId = $user->id;
            $user->delete();

            Log::info('Usuario eliminado exitosamente', ['user_id' => $userId]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
