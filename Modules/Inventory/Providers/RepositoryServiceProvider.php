<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * App repositories map
     */
    protected $repositories = [

        'Modules\Inventory\Repositories\Brands\BrandRepositoryInterface' => 'Modules\Inventory\Repositories\Brands\BrandRepository',
        'Modules\Inventory\Repositories\Categories\CategoryRepositoryInterface' => 'Modules\Inventory\Repositories\Categories\CategoryRepository',
        'Modules\Inventory\Repositories\Items\ItemRepositoryInterface' => 'Modules\Inventory\Repositories\Items\ItemRepository',
        'Modules\Inventory\Repositories\Quantities\QuantityRepositoryInterface' => 'Modules\Inventory\Repositories\Quantities\QuantityRepository',
        'Modules\Inventory\Repositories\Stores\StoreRepositoryInterface' => 'Modules\Inventory\Repositories\Stores\StoreRepository',
        'Modules\Inventory\Repositories\Transfers\TransferRepositoryInterface' => 'Modules\Inventory\Repositories\Transfers\TransferRepository',
        'Modules\Inventory\Repositories\Units\UnitRepositoryInterface' => 'Modules\Inventory\Repositories\Units\UnitRepository',
        'Modules\Inventory\Repositories\Taxes\TaxRepositoryInterface' => 'Modules\Inventory\Repositories\Taxes\TaxRepository',

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
