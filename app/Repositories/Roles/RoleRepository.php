<?php

namespace App\Repositories\Roles;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;

class RoleRepository extends BaseEloquentRepository implements RoleRepositoryInterface
{
    /** @var string */
    protected $modelName = Role::class;


    /**
     * Return a new query builder instance
     *
     * @return mixed
     * @throws \Exception#
     */
    public function getQueryBuilder($fields = ['*'])
    {
        // hide super_admin role
        return $this->getNewInstance()->newQuery()->where('id', '!=', '1');
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

        // start transaction create role with permission or nothing at all
        DB::beginTransaction();
        try {
            // create role
            $role = $this->executeSave([
                'name' => Arr::get($data, 'name'),
                'display_name' => Arr::get($data, 'display_name'),
                'description' => Arr::get($data, 'description'),
            ]);

            dd(Arr::get($data, 'permissions'));
            // assign permissions
            $role->attachPermissions(Arr::get($data, 'permissions'));

            // commit changes
            DB::commit();
            return $role;
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

            $role = $this->executeSave([
                'name' => Arr::get($data, 'name'),
                'display_name' => Arr::get($data, 'display_name'),
                'description' => Arr::get($data, 'description'),
            ]);

            // assign permissions
            $role->syncPermissions(Arr::get($data, 'permissions'));

            // commit changes
            DB::commit();

            return $role;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
