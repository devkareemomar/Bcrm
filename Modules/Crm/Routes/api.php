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
Route::group(['prefix' => 'v1/crm'], function () {

    Route::group(['middleware' => 'auth.api'], function () {
        //  sources Crud Routes
        Route::delete('sources', 'Sources\SourceController@bulkDestroy');
        Route::get('sources/brief', 'Sources\SourceController@brief');
        Route::resource('sources', 'Sources\SourceController')->except(['create', 'edit']);

        // Lead Stage Crud Routes
        Route::delete('lead-stages', 'LeadStages\LeadStageController@bulkDestroy');
        Route::get('lead-stages/brief', 'LeadStages\LeadStageController@brief');
        Route::resource('lead-stages', 'LeadStages\LeadStageController')->except(['create', 'edit']);

        // PaymentTerms Crud Routes
        Route::delete('payment-terms', 'PaymentTerms\PaymentTermController@bulkDestroy');
        Route::get('payment-terms/brief', 'PaymentTerms\PaymentTermController@brief');
        Route::get('payment-terms/type', 'PaymentTerms\PaymentTermController@PaymentType');
        Route::post('payment-terms/bulk-create', 'PaymentTerms\PaymentTermController@bulkStore');
        Route::resource('payment-terms', 'PaymentTerms\PaymentTermController')->except(['create', 'edit']);

        // Activity Logs Routes
        Route::get('leads-logs', 'Leads\LeadController@leadLogs');


    });
});


// Company Based Routes
Route::group(['prefix' => 'v1/companies/{company}/crm'], function () {
    Route::group(['middleware' => ['auth.api']], function () {
    });
});


Route::group(['prefix' => 'v1/branches/{branch}/crm'], function () {

    Route::group(['middleware' => ['auth.api', ResolveBranchMiddleware::class]], function () {

        // Clients Crud Routes
        Route::delete('clients', 'Clients\ClientController@bulkDestroy');
        Route::get('clients/brief', 'Clients\ClientController@brief');
        Route::get('clients/contacts/{client_id}', 'Clients\ClientController@contactByClient');

        Route::get('clients/example/download', 'Clients\ClientDataManagementController@downloadExampleExcel');
        Route::post('clients/import/upload', 'Clients\ClientDataManagementController@importData');

        Route::get('clients/attributes', 'Clients\ClientDataManagementController@attributes');
        Route::post('clients/export/download', 'Clients\ClientDataManagementController@downloadExport');

        Route::get('clients/statistics/branch', 'Clients\ClientStatisticController@statisticsClientByBranch');
        Route::get('clients/statistics/source', 'Clients\ClientStatisticController@statisticsSource');
        Route::get('clients/statistics/client-type', 'Clients\ClientStatisticController@statisticsClientType');

        Route::resource('clients', 'Clients\ClientController')->except(['create', 'edit']);

        // Leads Crud Routes
        Route::delete('leads', 'Leads\LeadController@bulkDestroy');
        Route::get('leads/brief', 'Leads\LeadController@brief');

        Route::get('leads/example/download', 'Leads\LeadDataManagementController@downloadExampleExcel');
        Route::post('leads/import/upload', 'Leads\LeadDataManagementController@importData');

        Route::get('leads/attributes', 'Leads\LeadDataManagementController@attributes');
        Route::post('leads/export/download', 'Leads\LeadDataManagementController@downloadExport');




        Route::get('leads/statistics/stage', 'Leads\LeadStatisticController@statisticsStage');
        Route::get('leads/statistics/source', 'Leads\LeadStatisticController@statisticsSource');
        Route::get('leads/statistics/lead-type', 'Leads\LeadStatisticController@statisticsLeadType');

        Route::get('leads/by-stage', 'Leads\LeadStatisticController@leadsByStage');
        Route::get('leads/convert/{id?}', 'Leads\LeadDataManagementController@convertToClient');
        Route::put('leads/update-stage/{id?}', 'Leads\LeadStatisticController@updateStageLead');
        Route::put('leads/stage-lead/', 'Leads\LeadStatisticController@bulkUpdateStageLead');

        Route::resource('leads', 'Leads\LeadController')->except(['create', 'edit']);

        // Opportunities Crud Routes
        Route::delete('opportunities', 'Opportunities\OpportunityController@bulkDestroy');
        Route::get('opportunities/brief', 'Opportunities\OpportunityController@brief');

        Route::get('opportunities/statistics/stage', 'Opportunities\OpportunityStatisticController@statisticsStage');
        Route::get('opportunities/statistics/source', 'Opportunities\OpportunityStatisticController@statisticsSource');

        Route::get('opportunities/attributes', 'Opportunities\OpportunityDataManagementController@attributes');
        Route::post('opportunities/export/download', 'Opportunities\OpportunityDataManagementController@downloadExport');


        Route::resource('opportunities', 'Opportunities\OpportunityController')->except(['create', 'edit']);


        // Opportunity Details Crud Routes
        Route::delete('opportunities/{opportunity_id}/details', 'Opportunities\OpportunityDetailsController@bulkDestroy');
        Route::resource('opportunities/{opportunity_id}/details', 'Opportunities\OpportunityDetailsController')->except(['create', 'edit']);


        // Quotations Crud Routes
        Route::delete('quotations', 'Quotations\QuotationController@bulkDestroy');
        Route::get('quotations/brief', 'Quotations\QuotationController@brief');

        Route::get('quotations/statistics/stage', 'Quotations\QuotationStatisticController@statisticsStage');
        Route::put('quotations/update-stage/{id?}', 'Quotations\QuotationStatisticController@updateStageLead');

        Route::get('quotations/attributes', 'Quotations\QuotationDataManagementController@attributes');
        Route::post('quotations/export/download', 'Quotations\QuotationDataManagementController@downloadExport');

        Route::resource('quotations', 'Quotations\QuotationController')->except(['create', 'edit']);

        // Quotations Details Crud Routes
        Route::delete('quotations/{quotation_id}/details', 'Quotations\QuotationDetailsController@bulkDestroy');
        Route::resource('quotations/{quotation_id}/details', 'Quotations\QuotationDetailsController')->except(['create', 'edit']);

        // SalesOrders Crud Routes
        Route::delete('sales-orders', 'SalesOrders\SalesOrderController@bulkDestroy');
        Route::get('sales-orders/brief', 'SalesOrders\SalesOrderController@brief');

        Route::get('sales-orders/statistics/stage', 'SalesOrders\SalesOrderStatisticController@statisticsStage');
        Route::put('sales-orders/update-stage/{id?}', 'SalesOrders\SalesOrderStatisticController@updateStageLead');

        Route::get('sales-orders/attributes', 'SalesOrders\SalesOrderDataManagementController@attributes');
        Route::post('sales-orders/export/download', 'SalesOrders\SalesOrderDataManagementController@downloadExport');

        Route::resource('sales-orders', 'SalesOrders\SalesOrderController')->except(['create', 'edit']);

        // SalesOrders Details Crud Routes
        Route::delete('sales-orders/{sales_order_id}/details', 'SalesOrders\SalesOrderDetailsController@bulkDestroy');
        Route::resource('sales-orders/{sales_order_id}/details', 'SalesOrders\SalesOrderDetailsController')->except(['create', 'edit']);


        // SalesInvoices Crud Routes
        Route::delete('sales-invoices', 'SalesInvoices\SalesInvoiceController@bulkDestroy');
        Route::get('sales-invoices/brief', 'SalesInvoices\SalesInvoiceController@brief');
        Route::resource('sales-invoices', 'SalesInvoices\SalesInvoiceController')->except(['create', 'edit']);

        // SalesOrders Details Crud Routes
        Route::delete('sales-invoices/{sales_invoice_id}/details', 'SalesInvoices\SalesInvoiceDetailsController@bulkDestroy');
        Route::resource('sales-invoices/{sales_invoice_id}/details', 'SalesInvoices\SalesInvoiceDetailsController')->except(['create', 'edit']);


        // Activities Crud Routes
        Route::delete('activities', 'Activities\ActivityController@bulkDestroy');
        Route::post('activities/bulk-create', 'Activities\ActivityController@bulkStore');
        Route::get('activities/by-date', 'Activities\ActivityController@activityByDate');
        Route::get('activities/brief', 'Activities\ActivityController@brief');
        Route::get('activities/type', 'Activities\ActivityController@activityType');
        Route::resource('activities', 'Activities\ActivityController')->except(['create', 'edit']);

        // Contacts Crud Routes
        Route::delete('contacts', 'Contacts\ContactController@bulkDestroy');
        Route::get('contacts/brief', 'Contacts\ContactController@brief');
        Route::get('contacts/type', 'Contacts\ContactController@contactType');
        Route::get('contacts/by-client/{client_id}', 'Contacts\ContactController@contactsByClient');

        Route::get('contacts/example/download', 'Contacts\ContactDataManagementController@downloadExampleExcel');
        Route::post('contacts/import/upload', 'Contacts\ContactDataManagementController@importData');

        Route::get('contacts/attributes', 'Contacts\ContactDataManagementController@attributes');
        Route::post('contacts/export/download', 'Contacts\ContactDataManagementController@downloadExport');


        Route::resource('contacts', 'Contacts\ContactController')->except(['create', 'edit']);



    });
});
