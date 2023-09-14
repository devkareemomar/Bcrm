<?php

namespace Modules\Core\Http\Controllers\Api\Branches;


use App\Http\Controllers\Api\ApiController;
use App\Repositories\Users\UserRepositoryInterface;
use Modules\Core\Http\Resources\Branches\UserBranchResource;
use Modules\Core\Http\Requests\Branches\UpdateUserBranchRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Branches\BranchUserRepositoryInterface;

class UserBranchController extends ApiController
{
    /** inject required classes */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected BranchUserRepositoryInterface $branchUserRepository
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

        $userBranches = $this->branchUserRepository->getAllBy(['user_id' => $user->id]);

        $result =  UserBranchResource::collection($userBranches);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function update(UpdateUserBranchRequest $request, $id)
    {
        $user = $this->userRepository->findById($id);
        // dd($user);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $this->branchUserRepository->destroyAllBy(['user_id' => $user->id]);

        // TODO: user insert for bulk store
        foreach ($request->branches_ids as $id) {
            $this->branchUserRepository->store(['user_id' => $user->id, 'branch_id' => $id]);
        }

        $userBranches = $this->branchUserRepository->getAllBy(['user_id' => $user->id]);

        $result = UserBranchResource::collection($userBranches);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("user branches updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }
}
