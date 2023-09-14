<?php

namespace App\Services\Api\Auth;

use Auth;
use Password;
use App\Services\Api\BaseApiService;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Resources\Auth\UserInfoResource;

class AuthService extends BaseApiService
{

    /**
     * login user and create new access token
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function login($requestData)
    {
        if (!$token = Auth::attempt($requestData)) {
            return $this->jsonResponse()->setStatus(false)
                ->setMessage(__('auth.invalid'))
                ->setCode(401);
        }

        $result = [
            'authToken' => $token,
            'expiresIn' => Auth::factory()->getTTL() * 60
        ];

        // return $this->jsonResponse()->setStatus(true)
        //     ->setMessage("successfully logged in.")
        //     ->setCode(200)
        //     ->setResult($result);

        return response()->json($result, 200);
    }


    /**
     * logout user and invalidate current token
     *
     * @return App\Classes\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("successfully logged out.")
            ->setCode(200);
    }

    /**
     * give quick info about the logged in user
     *
     * @return App\Classes\JsonResponse
     */
    public function info()
    {
        // dd($this->companies);
        // dd(Auth::user());
        $result =  new UserInfoResource(Auth::user());
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    /**
     * user forget password
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function forget($requestData)
    {
        // using same logic as ui auth package
        $broker = Password::broker();

        // send reset link and get translatable message
        $message = $broker->sendResetLink($requestData);


        // set right status code
        if ($message == $broker::RESET_THROTTLED) {
            $this->jsonResponse()->setStatus(false)->setCode(429);
        } else {
            $this->jsonResponse()->setStatus(true)->setCode(200);
        }

        return $this->jsonResponse()->setMessage(trans($message));
    }

    /**
     * reset user password
     *
     * @param array $requestData array of validated request data
     *
     * @return App\Classes\JsonResponse
     */
    public function reset($requestData)
    {
        // using same logic as ui auth package
        $broker = Password::broker();


        // reset password using callback and get translatable message
        $message = $broker->reset(
            $requestData,
            function ($user, $password) {
                // hash and set password
                $user->password = bcrypt($password);

                // save user model
                $user->save();

                // dispatch password reset event
                event(new PasswordReset($user));
            }
        );

        // set right status code
        if ($message == $broker::INVALID_TOKEN) {
            $this->jsonResponse()->setStatus(false)->setCode(400);
        } else {
            $this->jsonResponse()->setStatus(true)->setCode(200);
        }


        return $this->jsonResponse()->setMessage(trans($message));
    }
}
