<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        try {
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'activo' => $data['activo'] ?? true,
            ]);

            Log::info('Usuario creado exitosamente', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Limpiar caché del dashboard
            $this->clearDashboardCache();

            // Disparar evento
            event(new \App\Events\UserCreated($user));

            return $user;
        } catch (\Exception $e) {
            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'data' => $data,
                'ip' => request()->ip(),
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
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'activo' => $data['activo'] ?? false,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $this->userRepository->update($user, $updateData);

            Log::info('Usuario actualizado exitosamente', [
                'user_id' => $user->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Limpiar caché del dashboard
            $this->clearDashboardCache();

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
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
            $this->userRepository->delete($user);

            Log::info('Usuario eliminado exitosamente', [
                'user_id' => $userId,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Limpiar caché del dashboard
            $this->clearDashboardCache();

            return true;
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
            throw $e;
        }
    }

    /**
     * Clear dashboard cache.
     *
     * @return void
     */
    protected function clearDashboardCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('dashboard.stats');
        \Illuminate\Support\Facades\Cache::forget('dashboard.recent_users');
        \Illuminate\Support\Facades\Cache::forget('dashboard.all');
    }
}
