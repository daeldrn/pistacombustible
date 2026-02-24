<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        
        return ApiResponse::success($roles, 'Roles obtenidos exitosamente');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return ApiResponse::success(
            $role->load('permissions'),
            'Rol creado exitosamente',
            201
        );
    }

    /**
     * Display the specified role.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        
        return ApiResponse::success($role, 'Rol obtenido exitosamente');
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return ApiResponse::success(
            $role->load('permissions'),
            'Rol actualizado exitosamente'
        );
    }

    /**
     * Remove the specified role.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        
        // Prevenir eliminación de roles críticos
        if (in_array($role->name, ['admin', 'user'])) {
            return ApiResponse::error(
                'No se puede eliminar el rol ' . $role->name,
                null,
                403
            );
        }

        $role->delete();
        
        return ApiResponse::success(null, 'Rol eliminado exitosamente');
    }
}
