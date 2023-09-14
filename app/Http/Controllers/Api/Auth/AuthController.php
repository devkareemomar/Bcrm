<?php

namespace App\Http\Controllers\Api\Auth;

use App\Services\Api\Auth\AuthService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\ForgetPasswordRequest;

class AuthController extends ApiController
{

    /** inject required service classes */
    public function __construct(protected AuthService $authService)
    {
        /** Auth middleware */
        $this->middleware('auth:api', ['only' => ['logout', 'info']]);
    }


    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->validated());
    }



    public function logout()
    {
        return $this->authService->logout();;
    }


    public function info()
    {
        return $this->authService->info();
    }



    public function forget(ForgetPasswordRequest $request)
    {
        return $this->authService->forget($request->validated());
    }


    public function reset(ResetPasswordRequest $request)
    {
        return $this->authService->reset($request->validated());
    }
}
