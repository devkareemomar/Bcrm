<?php

namespace Modules\Core\Http\Controllers\Api\Teams;


use App\Http\Controllers\Api\ApiController;
use App\Repositories\Users\UserRepositoryInterface;
use Modules\Core\Http\Resources\Teams\UserTeamResource;
use Modules\Core\Http\Requests\Teams\UpdateUserTeamRequest;
use Modules\Core\Repositories\Teams\TeamUserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserTeamController extends ApiController
{
    /** inject required classes */
    public function __construct(
        protected TeamUserRepositoryInterface $teamUserRepository,
        protected UserRepositoryInterface $userRepository,
    ) {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_users')->only('show');
        $this->middleware('permission:update_users')->only('update');
    }


    public function show($id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $userTeams = $this->teamUserRepository->getAllBy(['user_id' => $user->id]);

        $result =  UserTeamResource::collection($userTeams);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }



    public function update(UpdateUserTeamRequest $request, $id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $this->teamUserRepository->destroyAllBy(['user_id' => $user->id]);

        // TODO: user insert for bulk store
        foreach ($request->teams_ids as $id) {
            $this->teamUserRepository->store(['user_id' => $user->id, 'team_id' => $id]);
        }

        $userTeams = $this->teamUserRepository->getAllBy(['user_id' => $user->id]);

        $result = UserTeamResource::collection($userTeams);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("user teams updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


}
