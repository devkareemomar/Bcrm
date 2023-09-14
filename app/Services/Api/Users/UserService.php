<?php

namespace App\Services\Api\Users;

use App\Services\FileService;
use App\Exports\Users\UserExport;
use Illuminate\Support\Facades\URL;
use App\Services\Api\BaseApiService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\BriefUserResource;
use App\Repositories\Users\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService extends BaseApiService
{

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected FileService $fileService
    ) {
    }

    /**
     * get list of all users
     *
     * @return App\Classes\JsonResponse
     */
    public function index()
    {
        $users = $this->userRepository->paginate('id', relationsCount: ['roles']);

        $result = new UserCollection($users);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * get list of all stores without pagination and in brief
     *
     * @return App\Classes\JsonResponse
     */
    public function brief()
    {
        $users = $this->userRepository->getAll(fields: ['id', 'name']);
        $result = BriefUserResource::collection($users);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * create new user
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function store($requestData)
    {

        $requestData['password'] = bcrypt($requestData['password']);

        $user = $this->userRepository->store($requestData);

        $result = new UserResource($user);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("user created successfully.")
            ->setCode(201)
            ->setResult($result);
    }

    /**
     * get user details
     *
     * @param int $id user id
     *
     * @return App\Classes\JsonResponse
     */
    public function show($id)
    {
        $user = $this->userRepository->findById($id, ["roles",'photo']);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $result =  new UserResource($user);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * update user
     *
     * @param array $requestData array of validated request data
     * @param int $id user id
     *
     * @return App\Classes\JsonResponse
     */
    public function update($requestData, $id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }



        if (isset($requestData['password'])) {
            $requestData['password'] = bcrypt($requestData['password']);
        }

        $user = $this->userRepository->updateByInstance($user, $requestData);

        $result =  new UserResource($user);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("user updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }

    /**
     * delete user
     *
     * @param int $id user id
     *
     * @return App\Classes\JsonResponse
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $this->fileService->deleteFile($user->photo); // delete photo

        $this->userRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("user deleted successfully.")
            ->setCode(200);
    }


    /**
     * delete bulk of users
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function bulkDestroy($requestData)
    {
        $ids = $requestData["ids"];

        $count = $this->userRepository->destroyAll($ids);

        $paths = $this->userRepository->pluckBy('id', $ids, 'photo');

        $this->fileService->deleteFiles($paths);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count users deleted successfully.")
            ->setCode(200);
    }



    public function export($requestData)
    {
        $result = URL::temporarySignedRoute(
            'users.export.download',
            now()->addMinutes(30),
            ['role_id' => $requestData['role_id']]
        );
        return $this->jsonResponse()->setStatus(true)
            ->setResult($result)
            ->setCode(200);
    }


    public function downloadExport($request)
    {
        if (!$request->hasValidSignature()) {
            return $this->jsonResponse()->setStatus(false)
                ->setMessage("url is invalid.")
                ->setCode(400);
        }

        $role_id = $request->query('role_id', null);
        return Excel::download(new UserExport($role_id), "users.xlsx");
    }
}
