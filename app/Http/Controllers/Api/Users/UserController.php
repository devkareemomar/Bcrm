<?php

namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Services\Api\Users\UserService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\ExportUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;

class UserController extends ApiController
{
    /** inject required service classes */
    public function __construct(protected UserService $userService)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_users')->only('index', 'show', 'export');
        $this->middleware('permission:create_users')->only('store');
        $this->middleware('permission:update_users')->only('update');
        $this->middleware('permission:delete_users')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        return $this->userService->index();
    }

    public function brief()
    {
        return $this->userService->brief();
    }

    public function store(StoreUserRequest $request)
    {
        return $this->userService->store($request->validated());
    }


    public function show($id)
    {
        return $this->userService->show($id);
    }



    public function update(UpdateUserRequest $request, $id)
    {
        return $this->userService->update($request->validated(), $id);
    }

    public function destroy($id)
    {
        return $this->userService->destroy($id);
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        return $this->userService->bulkDestroy($request->validated());
    }

    public function export(ExportUserRequest $request)
    {
        return $this->userService->export($request->validated());
    }

    public function downloadExport(Request $request)
    {
        return $this->userService->downloadExport($request);
    }
}
