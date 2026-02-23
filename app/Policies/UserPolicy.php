<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver la lista
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Los usuarios pueden ver su propio perfil o cualquier otro
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Todos los usuarios autenticados pueden crear usuarios
        // En producción, esto debería estar limitado a administradores
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        // Los usuarios pueden actualizar su propio perfil
        // En producción, agregar lógica para roles de administrador
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny('No tienes permiso para editar este usuario.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        // Los usuarios no pueden eliminarse a sí mismos
        // En producción, solo administradores deberían poder eliminar
        return $user->id !== $model->id
            ? Response::allow()
            : Response::deny('No puedes eliminar tu propia cuenta.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Solo administradores deberían poder restaurar usuarios
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Solo administradores deberían poder eliminar permanentemente
        return false;
    }
}
