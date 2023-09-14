<?php

namespace Modules\Crm\Repositories\SalesOrders;

use App\Exports\BaseExport;
use Modules\Crm\Models\SalesOrder;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Crm\Enums\SalesOrderStageEnum;
use Modules\Crm\Repositories\PaymentTerms\PaymentTermRepositoryInterface;

class SalesOrderRepository extends BaseEloquentRepository implements SalesOrderRepositoryInterface
{
    /** @var string */
    protected $modelName = SalesOrder::class;

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
            $data['code'] = 'SalesOrder_' . $this->maxId();

            $calculateTotal = $this->calculateTotal($data['items']);
            $data = array_merge($data, $calculateTotal);

            $salesorder = $this->executeSave(Arr::except($data, ['items','payment_terms']));
            $this->storePaymentTerms($data['payment_terms'], $salesorder->id);

            $this->storeSalesOrderDetails($data['items'], $salesorder->id);

            // commit changes
            DB::commit();
            return $salesorder;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }


    public function storePaymentTerms($payment_terms, $sales_order_id)
    {
        $payment_terms = collect($payment_terms)->map(function ($terms) use ($sales_order_id) {
            $terms['paymentable_id'] = $sales_order_id;
            $terms['paymentable_type'] = 'Modules\Crm\Models\SalesOrder';
            return $terms;
        });

        app()->make(PaymentTermRepositoryInterface::class)->insert($payment_terms->toArray());
    }

    public function storeSalesOrderDetails($items, $sales_order_id)
    {
        $items = $this->calculateItems($items);

        $items = collect($items)->map(function ($item) use ($sales_order_id) {
            $item['sales_order_id'] = $sales_order_id;
            return $item;
        });


        app()->make(SalesOrderDetailsRepositoryInterface::class)->insert($items->toArray());
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

     // calculation items for salesorder_detailes
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


         /**
     * return Statistics sales_orders by  Stage
     * @param int branchId
     */
    public function statisticsStage($branchId)
    {
        $stages = SalesOrderStageEnum::values();
        $statics = [];
        foreach ($stages as $key => $stage) {

          $count =  $this->count(['stage' => $stage]);
          $total =  array_sum($this->pluckBy('stage',$stage,'total_cost'));

          (['stage' => $stage]);
          $statics[$key] = [
                'name' => $stage,
                'sales_order_count' => $count,
                'total' => $total,
          ];

        }
        $result = [
            'total_sales_order_count' => $this->count(['branch_id' => $branchId]),
            'statics' => $statics,
      ];

      return  $result;
    }


      /**
     * download sales_orders into file excel
     * @param array request
     * @return array branchId
     */
    public function downloadExport($request, $branchId)
    {
        $fields = ($request->fields) ? $request->fields :$this->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        $sales_ordersCollection  = $this->getAll(
            relations: ['branch', 'department','quotation','opportunity', 'assign_to','approved_by','team','client','contact', 'document','currency'],
            parameters: ['branch_id' => $branchId],
            fields: $fields ?? ['*']
        );

        $name = 'sales_orders-' . random_int(100000, 999999) . '.xlsx';

        $sales_orders = $this->reMappingCollection($sales_ordersCollection);


        return Excel::download(new BaseExport($sales_orders, $fields), $name);
    }

     /**
     * return sales_orders collection after custom relationships
     * @param array sales_ordersCollection
     */
    protected function reMappingCollection($sales_ordersCollection)
    {
        $data =  $sales_ordersCollection->toArray();
        foreach ($sales_ordersCollection as $key =>  $sales_order) {
            $data[$key]['team_id'] = $sales_order->team->name ?? '';
            $data[$key]['assign_to_id'] = $sales_order->assign_to->name ?? '';
            $data[$key]['approved_by_id'] = $sales_order->approved_by->name ?? '';
            $data[$key]['currency_id'] = $sales_order->currency->name ?? '';
            $data[$key]['document_media_id']   = isset($sales_order->document) ? asset(Storage::url($sales_order->document->file)) : null;
            $data[$key]['client_id'] = isset($sales_order->client->first_name)? $sales_order->client->first_name .' '.$sales_order->client->last_name :'';
            $data[$key]['contact_id'] = isset($sales_order->contact->first_name)? $sales_order->contact->first_name .' '.$sales_order->contact->last_name :'';
            $data[$key]['department_id'] = $sales_order->department->name ?? '';
            $data[$key]['branch_id'] = $sales_order->branch->name ?? '';
            $data[$key]['quotation_id'] = $sales_order->quotation->code ?? '';
            $data[$key]['opportunity_id'] = $sales_order->opportunity->code ?? '';
            $data[$key] = Arr::except($data[$key], ['branch', 'department','quotation','opportunity', 'assign_to','approved_by','team','client','contact', 'document','currency']);
        }

        return collect($data);
    }


}
