<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * App repositories map
     */
    protected $repositories = [
        'Modules\Core\Repositories\Media\MediaRepositoryInterface' => 'Modules\Core\Repositories\Media\MediaRepository',

        'Modules\Core\Repositories\Countries\CountryRepositoryInterface' => 'Modules\Core\Repositories\Countries\CountryRepository',
        'Modules\Core\Repositories\States\StateRepositoryInterface' => 'Modules\Core\Repositories\States\StateRepository',
        'Modules\Core\Repositories\Cities\CityRepositoryInterface' => 'Modules\Core\Repositories\Cities\CityRepository',
        'Modules\Core\Repositories\Companies\CompanyRepositoryInterface' => 'Modules\Core\Repositories\Companies\CompanyRepository',
        'Modules\Core\Repositories\Branches\BranchRepositoryInterface' => 'Modules\Core\Repositories\Branches\BranchRepository',
        'Modules\Core\Repositories\Departments\DepartmentRepositoryInterface' => 'Modules\Core\Repositories\Departments\DepartmentRepository',
        'Modules\Core\Repositories\Teams\TeamRepositoryInterface' => 'Modules\Core\Repositories\Teams\TeamRepository',
        'Modules\Core\Repositories\Currencies\CurrencyRepositoryInterface' => 'Modules\Core\Repositories\Currencies\CurrencyRepository',

        'Modules\Core\Repositories\Teams\TeamUserRepositoryInterface' => 'Modules\Core\Repositories\Teams\TeamUserRepository',
        'Modules\Core\Repositories\Branches\BranchUserRepositoryInterface' => 'Modules\Core\Repositories\Branches\BranchUserRepository',
        'Modules\Core\Repositories\Classes\ClassRepositoryInterface' => 'Modules\Core\Repositories\Classes\ClassRepository',
        'Modules\Core\Repositories\ActivityLogs\ActivityLogRepositoryInterface' => 'Modules\Core\Repositories\ActivityLogs\ActivityLogRepository',
    ];


    /**
     * Register the service provider.
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
