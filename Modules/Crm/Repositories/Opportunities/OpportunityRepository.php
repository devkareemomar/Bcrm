<?php

namespace Modules\Crm\Repositories\Opportunities;

use App\Exports\BaseExport;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Crm\Enums\OpportunityStageEnum;
use Modules\Crm\Models\Opportunity;
use Modules\Crm\Repositories\PaymentTerms\PaymentTermRepositoryInterface;
use Modules\Crm\Repositories\Sources\SourceRepositoryInterface;

class OpportunityRepository extends BaseEloquentRepository implements OpportunityRepositoryInterface
{
    /** @var string */
    protected $modelName = Opportunity::class;

    protected $filterableFields = ['source_id','lead_id','department_id','team_id','assign_to_id','branch_id','stage','payment_type'];
    protected $searchableFields = ['code','subject','date'];



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
            $data['code'] = 'opportunity_' . $this->maxId();



            $calculateTotal = $this->calculateTotal($data['items']);
            $data = array_merge($data, $calculateTotal);

            $opportunity = $this->executeSave(Arr::except($data, ['items','payment_terms']));
            $this->storePaymentTerms($data['payment_terms'], $opportunity->id);

            $this->storeOpportunityDetails($data['items'], $opportunity->id);

            // commit changes
            DB::commit();
            return $opportunity;
        } catch (\Exception $e ) {
            DB::rollback();
            throw $e;
        }
    }



    public function storePaymentTerms($payment_terms, $opportunity_id)
    {
        $payment_terms = collect($payment_terms)->map(function ($terms) use ($opportunity_id) {
            $terms['paymentable_id'] = $opportunity_id;
            $terms['paymentable_type'] = 'Modules\Crm\Models\Opportunity';
            return $terms;
        });

        app()->make(PaymentTermRepositoryInterface::class)->insert($payment_terms->toArray());
    }

    public function storeOpportunityDetails($items, $opportunity_id)
    {
        $items = $this->calculateItems($items);

        $items = collect($items)->map(function ($item) use ($opportunity_id) {
            $item['opportunity_id'] = $opportunity_id;
            return $item;
        });

        app()->make(OpportunityDetailsRepositoryInterface::class)->insert($items->toArray());
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

    // calculation items for opportunity_detailes
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
     * return Statistics opportyunitis by  Stage
     * @param int branchId
     */
    public function statisticsStage()
    {
        $stages = OpportunityStageEnum::values();
        $result = [];
        foreach ($stages as $key => $stage) {

          $count =  $this->count(['stage' => $stage]);
          $result[$key] = [
                'name' => $stage,
                'opportunity_count' => $count,
          ];

        }

        // dd($result);

        return  $result;
    }

    /**
     * return Statistics opportyunitis by  Source
     * @param int branchId
     */
    public function statisticsSource($branchId)
    {
        return  app()->make(SourceRepositoryInterface::class)->getAll(relationCountWhere: 'opportunity', condition: ['branch_id' => $branchId], fields: ['id', 'name']);
    }



        /**
     * download opportyunitis into file excel
     * @param array request
     * @return array branchId
     */
    public function downloadExport($request, $branchId)
    {
        $fields = ($request->fields) ? $request->fields :$this->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        $opportyunitisCollection  = $this->getAll(
            relations: ['branch', 'source', 'department', 'assign_to','team','lead', 'document','currency'],
            parameters: ['branch_id' => $branchId],
            fields: $fields ?? ['*']
        );

        $name = 'opportyunitis-' . random_int(100000, 999999) . '.xlsx';

        $opportyunitis = $this->reMappingCollection($opportyunitisCollection);


        return Excel::download(new BaseExport($opportyunitis, $fields), $name);
    }

    /**
     * return opportyunitis collection after custom relationships
     * @param array opportyunitisCollection
     */
    protected function reMappingCollection($opportyunitisCollection)
    {
        $data =  $opportyunitisCollection->toArray();
        foreach ($opportyunitisCollection as $key =>  $opportyunity) {
            $data[$key]['branch_id'] = $opportyunity->branch->name ?? '';
            $data[$key]['source_id'] = $opportyunity->source->name ?? '';
            $data[$key]['team_id'] = $opportyunity->team->name ?? '';
            $data[$key]['lead_id'] = isset($opportyunity->lead->first_name)? $opportyunity->lead->first_name .' '.$opportyunity->lead->last_name :'';
            $data[$key]['currency_id'] = $opportyunity->currency->name ?? '';
            $data[$key]['department_id'] = $opportyunity->department->name ?? '';
            $data[$key]['assign_to_id'] = $opportyunity->assign_to->name ?? '';
            $data[$key]['document_media_id']   = isset($opportyunity->document) ? asset(Storage::url($opportyunity->document->file)) : null;
            $data[$key] = Arr::except($data[$key], ['branch', 'source', 'department', 'assign_to','team','lead', 'document','currency']);
        }

        return collect($data);
    }


}
