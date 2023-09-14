<?php

namespace Modules\Crm\Repositories\SalesInvoices;

use Modules\Crm\Models\SalesInvoice;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SalesInvoiceRepository extends BaseEloquentRepository implements SalesInvoiceRepositoryInterface
{
    /** @var string */
    protected $modelName = SalesInvoice::class;

    protected $filterableFields = [];
    protected $searchableFields = ['code'];


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

        DB::beginTransaction();
        try {

            // TOFIX:
            $data['code'] = 'salesinvoice_' . $this->maxId();

            $calculateTotal = $this->calculateTotal($data['items']);
            $data = array_merge($data, $calculateTotal);

            $salesinvoice = $this->executeSave(Arr::except($data, 'items'));

            $this->storeSalesInvoiceDetails($data['items'], $salesinvoice->id);

            // commit changes
            DB::commit();
            return $salesinvoice;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function storeSalesInvoiceDetails($items, $sales_invoice_id)
    {
        $items = $this->calculateItems($items);

        $items = collect($items)->map(function ($item) use ($sales_invoice_id) {
            $item['sales_invoice_id'] = $sales_invoice_id;
            return $item;
        });


        app()->make(SalesInvoiceDetailsRepositoryInterface::class)->insert($items->toArray());
    }

     // calculation total for master table
     private function calculateTotal($items)
     {
         $data = ['sub_total' => 0 ,'total_tax' => 0 ,'total_discount' => 0 ,'total_cost' => 0];

         foreach ($items as $item) {
             $sub_total      =  $item['price'] * $item['quantity'];
             $tax_value      =  $sub_total * $item['tax_rate']/100;
             $total_discount =  $item['discount_value'];

             if($item['discount_type'] == 'percentage'){
                 $total_discount  =  $sub_total * $item['discount_value']/100;
             }

             $data['sub_total']       += $sub_total;
             $data['total_tax']       += $tax_value;
             $data['total_discount']  += $total_discount;
             $data['total_cost']      += ($sub_total + $tax_value) - $total_discount;
         }
         return  $data;
     }

     // calculation items for salesinvoice_detailes
     private function calculateItems($items)
     {
         foreach ($items as $key =>  $item) {
             $sub_total      =  $item['price'] * $item['quantity'];
             $tax_value      =  $sub_total * $item['tax_rate']/100;
             $discount_value =  $item['discount_value'];

             if($item['discount_type'] == 'percentage'){
                 $discount_value  =  $sub_total * $item['discount_value']/100;
             }

             $items[$key]['tax_value']      = $tax_value;
             $items[$key]['discount_value'] = $discount_value;
             $items[$key]['total']          = ($sub_total + $tax_value) - $discount_value;

         }
         return  $items;
     }


    public function maxId()
    {
        $instance = $this->getQueryBuilder();
        $id = $instance->max('id');
        return $id ? $id + 1 : 1;
    }
}
