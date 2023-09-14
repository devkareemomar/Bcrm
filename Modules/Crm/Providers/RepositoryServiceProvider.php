<?php

namespace Modules\Crm\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * App repositories map
     */
    protected $repositories = [
        'Modules\Crm\Repositories\LeadStages\LeadStageRepositoryInterface' => 'Modules\Crm\Repositories\LeadStages\LeadStageRepository',
        'Modules\Crm\Repositories\Sources\SourceRepositoryInterface' => 'Modules\Crm\Repositories\Sources\SourceRepository',
        'Modules\Crm\Repositories\Leads\LeadRepositoryInterface' => 'Modules\Crm\Repositories\Leads\LeadRepository',
        'Modules\Crm\Repositories\Clients\ClientRepositoryInterface' => 'Modules\Crm\Repositories\Clients\ClientRepository',
        'Modules\Crm\Repositories\Opportunities\OpportunityRepositoryInterface' => 'Modules\Crm\Repositories\Opportunities\OpportunityRepository',
        'Modules\Crm\Repositories\Opportunities\OpportunityDetailsRepositoryInterface' => 'Modules\Crm\Repositories\Opportunities\OpportunityDetailsRepository',
        'Modules\Crm\Repositories\Quotations\QuotationRepositoryInterface' => 'Modules\Crm\Repositories\Quotations\QuotationRepository',
        'Modules\Crm\Repositories\Quotations\QuotationDetailsRepositoryInterface' => 'Modules\Crm\Repositories\Quotations\QuotationDetailsRepository',
        'Modules\Crm\Repositories\SalesOrders\SalesOrderRepositoryInterface' => 'Modules\Crm\Repositories\SalesOrders\SalesOrderRepository',
        'Modules\Crm\Repositories\SalesOrders\SalesOrderDetailsRepositoryInterface' => 'Modules\Crm\Repositories\SalesOrders\SalesOrderDetailsRepository',
        'Modules\Crm\Repositories\SalesInvoices\SalesInvoiceRepositoryInterface' => 'Modules\Crm\Repositories\SalesInvoices\SalesInvoiceRepository',
        'Modules\Crm\Repositories\SalesInvoices\SalesInvoiceDetailsRepositoryInterface' => 'Modules\Crm\Repositories\SalesInvoices\SalesInvoiceDetailsRepository',
        'Modules\Crm\Repositories\Activities\ActivityRepositoryInterface' => 'Modules\Crm\Repositories\Activities\ActivityRepository',
        'Modules\Crm\Repositories\Contacts\ContactRepositoryInterface' => 'Modules\Crm\Repositories\Contacts\ContactRepository',
        'Modules\Crm\Repositories\PaymentTerms\PaymentTermRepositoryInterface' => 'Modules\Crm\Repositories\PaymentTerms\PaymentTermRepository',
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
