<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Get paginated users.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update a user.
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete a user (soft delete).
     *
     * @param User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    /**
     * Get all users.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * Get active users.
     *
     * @return Collection
     */
    public function getActive(): Collection
    {
        return User::where('activo', true)->get();
    }

    /**
     * Get inactive users.
     *
     * @return Collection
     */
    public function getInactive(): Collection
    {
        return User::where('activo', false)->get();
    }

    /**
     * Get recent users.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecent(int $limit = 10): Collection
    {
        return User::latest()->take($limit)->get();
    }

    /**
     * Count all users.
     *
     * @return int
     */
    public function count(): int
    {
        return User::count();
    }

    /**
     * Count active users.
     *
     * @return int
     */
    public function countActive(): int
    {
        return User::where('activo', true)->count();
    }

    /**
     * Count inactive users.
     *
     * @return int
     */
    public function countInactive(): int
    {
        return User::where('activo', false)->count();
    }
}
