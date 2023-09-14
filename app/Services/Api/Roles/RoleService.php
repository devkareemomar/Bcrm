<?php

namespace App\Services\Api\Roles;

use App\Services\Api\BaseApiService;
use App\Http\Resources\Roles\RoleResource;
use App\Http\Resources\Roles\RoleCollection;
use App\Http\Resources\Roles\BriefRoleResource;
use App\Repositories\Roles\RoleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleService extends BaseApiService
{

    public function __construct(protected RoleRepositoryInterface $roleRepository)
    {
    }

    /**
     * get list of all roles
     *
     * @return App\Classes\JsonResponse
     */
    public function index()
    {
        $roles = $this->roleRepository->paginate('id', relationsCount: ['permissions', 'users']);

        $result = new RoleCollection($roles);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    /**
     * get list of all roles without pagination and in brief
     *
     * @return App\Classes\JsonResponse
     */
    public function brief()
    {
        $roles = $this->roleRepository->getAll(fields: ['id', 'display_name']);

        $result = BriefRoleResource::collection($roles);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * create new role
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function store($requestData)
    {
        $role = $this->roleRepository->store($requestData);

        $result = new RoleResource($role);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("role created successfully.")
            ->setCode(201)
            ->setResult($result);
    }

    /**
     * get role details
     *
     * @param int $id role id
     *
     * @return App\Classes\JsonResponse
     */
    public function show($id)
    {
        $role = $this->roleRepository->findById($id, ['permissions'], ['users']);

        if (!$role) {
            throw new NotFoundHttpException();
        }

        $result =  new RoleResource($role);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * update role details
     *
     * @param array $requestData array of validated request data
     * @param int $id role id
     *
     * @return App\Classes\JsonResponse
     */
    public function update($requestData, $id)
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            throw new NotFoundHttpException();
        }

        $role = $this->roleRepository->updateByInstance($role, $requestData);

        $role = $this->roleRepository->findById($id, ['permissions'], ['users']);

        $result = new RoleResource($role);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("role updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * delete role
     *
     * @param int $id role id
     *
     * @return App\Classes\JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            throw new NotFoundHttpException();
        }

        $this->roleRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("role deleted successfully.")
            ->setCode(200);
    }


    /**
     * delete bulk of roles
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function bulkDestroy($requestData)
    {
        $ids = $requestData["ids"];

        $count = $this->roleRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count roles deleted successfully.")
            ->setCode(200);
    }
}
