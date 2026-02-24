<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all();
        
        return ApiResponse::success($permissions, 'Permisos obtenidos exitosamente');
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name|max:255',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return ApiResponse::success($permission, 'Permiso creado exitosamente', 201);
    }

    /**
     * Display the specified permission.
     */
    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);
        
        return ApiResponse::success($permission, 'Permiso obtenido exitosamente');
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $permission->update(['name' => $request->name]);

        return ApiResponse::success($permission, 'Permiso actualizado exitosamente');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        
        return ApiResponse::success(null, 'Permiso eliminado exitosamente');
    }
}
