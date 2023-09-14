<?php


namespace App\Repositories\Users;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface UserRepositoryInterface extends BaseEloquentRepositoryInterface
{

    /**
     * get all users by role for data export
     *
     * @param int|null $role_id
     * @return mixed
     * @throws \Exception
     */
    public function getAllUsersByRole($role_id = null);
}
