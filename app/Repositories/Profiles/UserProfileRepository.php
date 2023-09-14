<?php

namespace App\Repositories\Profiles;

use App\Models\User;
use App\Repositories\BaseEloquentRepository;

class  UserProfileRepository extends BaseEloquentRepository implements UserProfileRepositoryInterface
{
    /** @var string */
    protected $modelName = User::class;
}
