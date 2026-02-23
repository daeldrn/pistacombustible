<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AuthorizesRequests;
    
    protected UserService $userService;
    protected UserRepository $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $users = $this->userRepository->paginate($perPage);

        return ApiResponse::success(
            new UserCollection($users),
            'Lista de usuarios obtenida exitosamente'
        );
    }

    /**
     * Store a newly created user
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return ApiResponse::created(
                UserResource::make($user),
                'Usuario creado exitosamente'
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError('Error al crear el usuario');
        }
    }

    /**
     * Display the specified user
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return ApiResponse::success(
            UserResource::make($user),
            'Usuario obtenido exitosamente'
        );
    }

    /**
     * Update the specified user
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $this->authorize('update', $user);
            
            $user = $this->userService->updateUser($user, $request->validated());

            return ApiResponse::success(
                UserResource::make($user),
                'Usuario actualizado exitosamente'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return ApiResponse::forbidden('No autorizado para realizar esta acción');
        } catch (\Exception $e) {
            return ApiResponse::serverError('Error al actualizar el usuario');
        }
    }

    /**
     * Remove the specified user
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->authorize('delete', $user);
            
            $this->userService->deleteUser($user);

            return ApiResponse::success(null, 'Usuario eliminado exitosamente');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return ApiResponse::forbidden('No autorizado para realizar esta acción');
        } catch (\Exception $e) {
            return ApiResponse::serverError('Error al eliminar el usuario');
        }
    }
}
