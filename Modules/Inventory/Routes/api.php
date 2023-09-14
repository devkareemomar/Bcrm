<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Middleware\ResolveBranchMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Global Based Routes
Route::group(['prefix' => 'v1/inventory'], function () {

    Route::group(['middleware' => 'auth.api'], function () {
        // Taxes Crud Routes
        Route::delete('taxes', 'Taxes\TaxController@bulkDestroy');
        Route::get('taxes/brief', 'Taxes\TaxController@brief');
        Route::resource('taxes', 'Taxes\TaxController')->except(['create', 'edit']);

        // Units Crud Routes
        Route::delete('units', 'Units\UnitController@bulkDestroy');
        Route::get('units/brief', 'Units\UnitController@brief');
        Route::resource('units', 'Units\UnitController')->except(['create', 'edit']);

        // Transfers Crud Routes
        // Route::delete('transfers', 'Transfers\TransferController@bulkDestroy');
        // Route::get('transfers/brief', 'Transfers\TransferController@brief');
        Route::resource('transfers', 'Transfers\TransferController')->only(['index', 'store','update', 'show']);
    });
});


// Company Based Routes
Route::group(['prefix' => 'v1/companies/{company}/inventory'], function () {
    Route::group(['middleware' => ['auth.api']], function () {
        // Categories Crud Routes
        Route::delete('categories', 'Categories\CategoryController@bulkDestroy');
        Route::get('categories/brief', 'Categories\CategoryController@brief');
        Route::resource('categories', 'Categories\CategoryController')->except(['create', 'edit']);

        // Brands Crud Routes
        Route::delete('brands', 'Brands\BrandController@bulkDestroy');
        Route::get('brands/brief', 'Brands\BrandController@brief');
        Route::resource('brands', 'Brands\BrandController')->except(['create', 'edit']);

        // Items Crud Routes
        Route::delete('items', 'Items\ItemController@bulkDestroy');
        Route::get('items/brief', 'Items\ItemController@brief');
        Route::resource('items', 'Items\ItemController')->except(['create', 'edit']);
    });
});




// Branches Based Routes
Route::group(['prefix' => 'v1/branches/{branch}/inventory'], function () {
    Route::group(['middleware' => ['auth.api', ResolveBranchMiddleware::class]], function () {
        // Stores Crud Routes
        Route::delete('stores', 'Stores\StoreController@bulkDestroy');
        Route::get('stores/brief', 'Stores\StoreController@brief');
        Route::resource('stores', 'Stores\StoreController')->except(['create', 'edit']);

        // Store Quantities Crud Routes
        Route::prefix('stores/{store}')->group(function () {
            Route::delete('quantities', 'Quantities\QuantityController@bulkDestroy');
            Route::get('quantities/brief', 'Quantities\QuantityController@brief');
            Route::resource('quantities', 'Quantities\QuantityController')->except(['create', 'edit']);
        });
    });
});
