<?php

namespace Modules\Crm\Repositories\Opportunities;

use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Crm\Models\OpportunityDetails;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OpportunityDetailsRepository extends BaseEloquentRepository implements OpportunityDetailsRepositoryInterface
{
    /** @var string */
    protected $modelName = OpportunityDetails::class;

    protected $filterableFields = [];
    protected $searchableFields = [];

    /**
     * create a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function store($data)
    {
        $this->instance =$this->getNewInstance();

        DB::beginTransaction();
        try {

            $data =  $this->calculation($data);
            $opportunityDetails = $this->executeSave($data);

            $masterData = $this->calculateMasterData($opportunityDetails->opportunity_id);

            $data['item']   = $opportunityDetails;
            $data['master'] = $masterData;

            // commit changes
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * update a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateByInstance($opportunityDetails,$data)
    {
        $this->instance = $opportunityDetails;

        DB::beginTransaction();
        try {

            $data =  $this->calculation($data);
            $opportunityDetails = $this->executeSave($data);

            $masterData = $this->calculateMasterData($opportunityDetails->opportunity_id);

            $data['item']   = $opportunityDetails;
            $data['master'] = $masterData;

            // commit changes
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }


    private function calculation($item)
    {
        $sub_total      =  $item['price'] * $item['quantity'];
        $tax_value      =  $sub_total * $item['tax_rate']/100;
        $discount_value =  $item['discount_value'];

        if($item['discount_type'] == 'percentage'){
            $discount_value  =  $sub_total * $item['discount_value']/100;
        }

        $item['tax_value']      = $tax_value;
        $item['discount_value'] = $discount_value;
        $item['total']          = ($sub_total + $tax_value) - $discount_value;

        return  $item;
    }


    private function calculateMasterData($opportunity_id)
    {

        $items  = $this->getAllBy(['opportunity_id' => $opportunity_id],fields:['price','quantity','tax_value','discount_type','discount_value','total'])->toArray();

        if (!$items) {
            throw new NotFoundHttpException();
        }

        $data = ['sub_total' => 0 ,'total_tax' => 0 ,'total_discount' => 0 ,'total_cost' => 0];

        foreach ($items as $item) {
            $sub_total      =  $item['price'] * $item['quantity'];
            $total_discount =  $item['discount_value'];

            if($item['discount_type'] == 'percentage'){
                $total_discount  =  $sub_total * $item['discount_value']/100;
            }

            $data['sub_total']       += $sub_total;
            $data['total_tax']       += $item['tax_value'];
            $data['total_discount']  += $total_discount;
            $data['total_cost']      += ($sub_total + $item['tax_value']) - $total_discount;
        }

        app()->make(OpportunityRepositoryInterface::class)->updateById($opportunity_id, $data);

        return $data;
    }


    /**
     * delete a  record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function delete($id,$opportunity_id)
    {
        $opportunityDetails = $this->findById($id);

        if (!$opportunityDetails) {
            throw new NotFoundHttpException();
        }


        DB::beginTransaction();
        try {

            $this->destroy($id);
            $masterData = $this->calculateMasterData($opportunity_id);

            // commit changes
            DB::commit();
            return $masterData;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * delete All records
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function deleteAll($ids,$opportunity_id)
    {

        DB::beginTransaction();
        try {

            $count = $this->destroyAll($ids);
            $masterData = $this->calculateMasterData($opportunity_id);

            $data['count']   = $count;
            $data['master'] = $masterData;

            // commit changes
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }







}
