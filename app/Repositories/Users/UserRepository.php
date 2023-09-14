<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;

class UserRepository extends BaseEloquentRepository implements UserRepositoryInterface
{
    /** @var string */
    protected $modelName = User::class;

    protected $searchableFields = ['name', 'email'];


    /**
     * Return a new query builder instance
     *
     * @return mixed
     * @throws \Exception#
     */
    public function getQueryBuilder($fields = ['*'])
    {
        // hide super_admin user
        return $this->getNewInstance()->newQuery()->select($fields)->where('id', '!=', '1');
    }

    /**
     * Create a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->instance = $this->getNewInstance();

        // start transaction create user with roles or nothing at all
        DB::beginTransaction();
        try {


            $user = $this->executeSave(Arr::except($data,'roles_ids'));

            // assign roles
            $user->attachRoles($data['roles_ids']);

            // commit changes
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }


    /**
     * update model instance
     *
     * @param object $instance model instance
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateByInstance($instance, array $data)
    {
        $this->instance = $instance;

        // start transaction create role with permission or nothing at all
        DB::beginTransaction();
        try {


            if (isset($data['password'])) {
                $this->instance->password = $data['password'];
            }


            $user = $this->executeSave(Arr::except($data,'roles_ids'));

            // assign permissions
            $user->syncRoles($data['roles_ids']);

            // commit changes
            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }



    /**
     * get all users by role for data export
     *
     * @param int|null $role_id
     * @return mixed
     * @throws \Exception
     */
    public function getAllUsersByRole($role_id = null)
    {
        $query =  DB::table('role_user')
            ->select(['users.name', 'email', 'users.created_at', 'display_name AS role'])
            ->join('users', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_id', '!=', 1)
            ->where('user_id', '!=', 1);

        if (!$role_id) {
            return $query->get();
        }

        return $query->where('role_id', '=', $role_id)->get();
    }
}
