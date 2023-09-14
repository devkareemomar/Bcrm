<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * App repositories map
     */
    protected $repositories = [
        'App\Repositories\Roles\RoleRepositoryInterface' => 'App\Repositories\Roles\RoleRepository',
        'App\Repositories\Users\UserRepositoryInterface' => 'App\Repositories\Users\UserRepository',
        'App\Repositories\Profiles\UserProfileRepositoryInterface' => 'App\Repositories\Profiles\UserProfileRepository',
    ];


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register app repositories
        foreach ($this->repositories as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
