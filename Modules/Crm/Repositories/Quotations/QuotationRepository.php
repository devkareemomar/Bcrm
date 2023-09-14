<?php

namespace Modules\Crm\Repositories\Quotations;

use App\Exports\BaseExport;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Crm\Enums\QuotationStageEnum;
use Modules\Crm\Models\Quotation;
use Modules\Crm\Repositories\PaymentTerms\PaymentTermRepositoryInterface;

class QuotationRepository extends BaseEloquentRepository implements QuotationRepositoryInterface
{
    /** @var string */
    protected $modelName = Quotation::class;


    protected $filterableFields = ['stage','payment_type','team_id','assign_to_id','currency_id','client_id','contact_id','department_id','branch_id'];
    protected $searchableFields = ['code','subject','start_date','end_date'];

    public function maxId()
    {
        $instance = $this->getQueryBuilder();
        $id = $instance->max('id');
        return $id ? $id + 1 : 1;
    }


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
            $data['code'] = 'Quotation_' . $this->maxId();

            $calculateTotal = $this->calculateTotal($data['items']);
            $data = array_merge($data, $calculateTotal);

            $quotation = $this->executeSave(Arr::except($data, ['items','payment_terms']));
            $this->storePaymentTerms($data['payment_terms'], $quotation->id);

            $this->storeQuotationDetails($data['items'], $quotation->id);

            // commit changes
            DB::commit();
            return $quotation;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }


    public function storePaymentTerms($payment_terms, $quotation_id)
    {
        $payment_terms = collect($payment_terms)->map(function ($terms) use ($quotation_id) {
            $terms['paymentable_id'] = $quotation_id;
            $terms['paymentable_type'] = 'Modules\Crm\Models\Quotation';
            return $terms;
        });

        app()->make(PaymentTermRepositoryInterface::class)->insert($payment_terms->toArray());
    }


    public function storeQuotationDetails($items, $quotation_id)
    {
        $items = $this->calculateItems($items);

        $items = collect($items)->map(function ($item) use ($quotation_id) {
            $item['quotation_id'] = $quotation_id;
            return $item;
        });


        app()->make(QuotationDetailsRepositoryInterface::class)->insert($items->toArray());
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

    // calculation items for quotation_detailes
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


     /**
     * return Statistics quotations by  Stage
     * @param int branchId
     */
    public function statisticsStage($branchId)
    {
        $stages = QuotationStageEnum::values();
        $statics = [];
        foreach ($stages as $key => $stage) {

          $count =  $this->count(['stage' => $stage]);
          $total =  array_sum($this->pluckBy('stage',$stage,'total_cost'));

          $statics[$key] = [
                'name' => $stage,
                'quotation_count' => $count,
                'total' => $total,
          ];

        }
        $result = [
              'total_quotation_count' => $this->count(['branch_id' => $branchId]),
              'statics' => $statics,
        ];

        return  $result;
    }


      /**
     * download quotations into file excel
     * @param array request
     * @return array branchId
     */
    public function downloadExport($request, $branchId)
    {
        $fields = ($request->fields) ? $request->fields :$this->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        $quotationsCollection  = $this->getAll(
            relations: ['branch', 'department', 'assign_to','team','client','contact', 'document','currency'],
            parameters: ['branch_id' => $branchId],
            fields: $fields ?? ['*']
        );

        $name = 'quotations-' . random_int(100000, 999999) . '.xlsx';

        $quotations = $this->reMappingCollection($quotationsCollection);


        return Excel::download(new BaseExport($quotations, $fields), $name);
    }

     /**
     * return quotations collection after custom relationships
     * @param array quotationsCollection
     */
    protected function reMappingCollection($quotationsCollection)
    {
        $data =  $quotationsCollection->toArray();
        foreach ($quotationsCollection as $key =>  $quotation) {
            $data[$key]['team_id'] = $quotation->team->name ?? '';
            $data[$key]['assign_to_id'] = $quotation->assign_to->name ?? '';
            $data[$key]['currency_id'] = $quotation->currency->name ?? '';
            $data[$key]['document_media_id']   = isset($quotation->document) ? asset(Storage::url($quotation->document->file)) : null;
            $data[$key]['client_id'] = isset($quotation->client->first_name)? $quotation->client->first_name .' '.$quotation->client->last_name :'';
            $data[$key]['contact_id'] = isset($quotation->contact->first_name)? $quotation->contact->first_name .' '.$quotation->contact->last_name :'';
            $data[$key]['department_id'] = $quotation->department->name ?? '';
            $data[$key]['branch_id'] = $quotation->branch->name ?? '';
            $data[$key] = Arr::except($data[$key], ['branch', 'department', 'assign_to','team','client','contact', 'document','currency']);
        }

        return collect($data);
    }
}
