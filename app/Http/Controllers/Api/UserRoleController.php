<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    /**
     * Asignar roles a un usuario
     */
    public function assignRoles(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $user->syncRoles($request->roles);

        return ApiResponse::success(
            $user->load('roles'),
            'Roles asignados exitosamente'
        );
    }

    /**
     * Asignar permisos directos a un usuario
     */
    public function assignPermissions(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $user->syncPermissions($request->permissions);

        return ApiResponse::success(
            $user->load('permissions'),
            'Permisos asignados exitosamente'
        );
    }

    /**
     * Obtener roles y permisos de un usuario
     */
    public function getUserPermissions($userId)
    {
        $user = User::with(['roles.permissions', 'permissions'])->findOrFail($userId);

        $data = [
            'user' => $user->only(['id', 'name', 'email']),
            'roles' => $user->roles,
            'direct_permissions' => $user->permissions,
            'all_permissions' => $user->getAllPermissions(),
        ];

        return ApiResponse::success($data, 'Permisos del usuario obtenidos exitosamente');
    }

    /**
     * Remover un rol de un usuario
     */
    public function removeRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $user->removeRole($request->role);

        return ApiResponse::success(
            $user->load('roles'),
            'Rol removido exitosamente'
        );
    }

    /**
     * Remover un permiso directo de un usuario
     */
    public function revokePermission(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'permission' => 'required|string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $user->revokePermissionTo($request->permission);

        return ApiResponse::success(
            $user->load('permissions'),
            'Permiso revocado exitosamente'
        );
    }
}
