<?php

namespace App\Repositories;

class BaseEloquentBranchRepository extends BaseEloquentRepository
{
    /**
     * Returns new model instance
     *
     * @return mixed
     * @throws \Exception
     */
    public function getNewInstance()
    {
        $model = $this->getModelName();

        $instance = new $model;

        $instance->branch_id = current_branch_id();

        return $instance;
    }


    /**
     * Return a new query builder instance
     *
     * @return mixed
     * @throws \Exception#
     */
    public function getQueryBuilder($fields = ['*'])
    {
        return $this->getNewInstance()->newQuery()->select($fields)->where('branch_id', current_branch_id());
    }
}
