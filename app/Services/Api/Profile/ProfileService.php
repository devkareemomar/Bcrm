<?php

namespace App\Services\Api\Profile;

use Auth;
use App\Services\FileService;
use App\Services\Api\BaseApiService;
use App\Http\Resources\Profile\UserProfileResource;
use App\Repositories\Profiles\UserProfileRepositoryInterface;

class ProfileService extends BaseApiService
{
    public function __construct(
        protected UserProfileRepositoryInterface $userProfileRepository,
        protected FileService $fileService
    ) {
    }

    /**
     * get user profile data
     * 
     * @return App\Classes\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        $result = new UserProfileResource($user);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    /**
     * update user profile data
     * 
     * @param array $requestData array of validated request dat
     * 
     * @return App\Classes\JsonResponse
     */
    public function update($requestData)
    {
        $user = Auth::user();

        if (isset($requestData['password'])) {
            $requestData['password'] = bcrypt($requestData['password']);
        }

        // save photo
        if (isset($requestData['photo'])) {
            $requestData['photo'] = $this->fileService->savePublicFile($requestData['photo'], "users");
            $this->fileService->deleteFile($user->photo); // delete old photo
        }


        $updatedUser = $this->userProfileRepository->updateByInstance($user, $requestData);

        $result =  new UserProfileResource($updatedUser);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("profile updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }
}
