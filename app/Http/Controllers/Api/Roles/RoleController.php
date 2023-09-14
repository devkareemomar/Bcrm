<?php

namespace App\Http\Controllers\Api\Roles;

use App\Services\Api\Roles\RoleService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use App\Http\Requests\Api\Role\StoreRoleRequest;
use App\Http\Requests\Api\Role\UpdateRoleRequest;

class RoleController extends ApiController
{
    /** inject required service classes */
    public function __construct(protected RoleService $roleService)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('role:super_admin')->except('brief');
        $this->middleware('permission:create_users,update_users')->only('brief');
    }

    public function index()
    {
        return $this->roleService->index();;
    }

    public function brief()
    {
        return $this->roleService->brief();
    }


    public function store(StoreRoleRequest $request)
    {
        return $this->roleService->store($request->validated());
    }


    public function show($id)
    {
        return $this->roleService->show($id);
    }


    public function update(UpdateRoleRequest $request, $id)
    {
        return $this->roleService->update($request->validated(), $id);
    }


    public function destroy($id)
    {
        return $this->roleService->destroy($id);
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        return $this->roleService->bulkDestroy($request->validated());
    }
}
