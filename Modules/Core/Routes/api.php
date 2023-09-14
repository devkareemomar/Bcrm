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

Route::group(['prefix' => 'v1/core'], function () {

    // Guarded Routes
    Route::group(['middleware' => 'auth.api'], function () {
        // Countries Crud Routes
        Route::delete('countries', 'Countries\CountryController@bulkDestroy');
        Route::get('countries/brief', 'Countries\CountryController@brief');
        Route::resource('countries', 'Countries\CountryController')->except(['create', 'edit']);

        // States Crud Routes
        Route::delete('states', 'States\StateController@bulkDestroy');
        Route::get('states/brief', 'States\StateController@brief');
        Route::resource('states', 'States\StateController')->except(['create', 'edit']);

        // Cities Crud Routes
        Route::delete('cities', 'Cities\CityController@bulkDestroy');
        Route::get('cities/brief', 'Cities\CityController@brief');
        Route::resource('cities', 'Cities\CityController')->except(['create', 'edit']);

        // Companies Crud Routes
        Route::delete('companies', 'Companies\CompanyController@bulkDestroy');
        Route::get('companies/brief', 'Companies\CompanyController@brief');
        Route::get('companies/brief-company-branches', 'Companies\CompanyController@briefCompanyBranches');
        Route::resource('companies', 'Companies\CompanyController')->except(['create', 'edit']);

        // Branches Crud Routes
        Route::delete('branches', 'Branches\BranchController@bulkDestroy');
        Route::get('branches/brief', 'Branches\BranchController@brief');
        Route::get('branches/brief-company/{company_id}', 'Branches\BranchController@briefByCompanyId');
        Route::resource('branches', 'Branches\BranchController')->except(['create', 'edit']);

        // Currencies Crud Routes
        Route::delete('currencies', 'Currencies\CurrencyController@bulkDestroy');
        Route::get('currencies/brief', 'Currencies\CurrencyController@brief');
        Route::resource('currencies', 'Currencies\CurrencyController')->except(['create', 'edit']);

        // Media Routes
        Route::delete('media', 'Media\MediaController@bulkDestroy');
        Route::resource('media', 'Media\MediaController')->except(['create', 'edit', 'update']);

        // Teams Crud Routes
        Route::delete('teams', 'Teams\TeamController@bulkDestroy');
        Route::get('teams/brief', 'Teams\TeamController@brief');
        Route::get('teams/users/{team_id}', 'Teams\TeamController@usersByTeam');
        Route::resource('teams', 'Teams\TeamController')->except(['create', 'edit']);

        // User Branches Routes
        Route::get('user-branches/{user}', 'Branches\UserBranchController@show');
        Route::patch('user-branches/{user}', 'Branches\UserBranchController@update');

        // User Teams Routes
        Route::get('user-teams/{user}', 'Teams\UserTeamController@show');
        Route::patch('user-teams/{user}', 'Teams\UserTeamController@update');


        // Activity Logs Routes
        Route::get('activity-logs', 'ActivityLogs\ActivityLogController@index');

    });
});


// Company Based Routes
Route::group(['prefix' => 'v1/companies/{company}/core'], function () {
    Route::group(['middleware' => ['auth.api']], function () {

        // Classes Crud Routes
        Route::delete('classes', 'Classes\ClassController@bulkDestroy');
        Route::get('classes/brief', 'Classes\ClassController@brief');
        Route::resource('classes', 'Classes\ClassController');
    });
});



Route::group(['prefix' => 'v1/branches/{branch}/core'], function () {

    // Guarded Routes
    Route::group(['middleware' => ['auth.api', ResolveBranchMiddleware::class]], function () {
        // Departments Crud Routes
        Route::delete('departments', 'Departments\DepartmentController@bulkDestroy');
        Route::get('departments/brief', 'Departments\DepartmentController@brief');
        Route::resource('departments', 'Departments\DepartmentController')->except(['create', 'edit']);
    });
});


