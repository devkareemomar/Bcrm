<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Api\ApiController;
use App\Services\Api\Roles\PermissionService;

class PermissionController extends ApiController
{
    
    /** inject required service classes */
    public function __construct(protected PermissionService $permissionService)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('role:super_admin');
    }


    public function index()
    {
        return $this->permissionService->index();
    }
}
