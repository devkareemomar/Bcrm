<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\ApiController;
use App\Services\Api\Profile\ProfileService;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;

class ProfileController extends ApiController
{

    /** inject required service classes */
    public function __construct(protected ProfileService $profileService)
    {
    }


    public function index()
    {
        return $this->profileService->index();
    }




    public function update(UpdateProfileRequest $request)
    {
        return $this->profileService->update($request->validated());
    }
}
