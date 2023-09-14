<?php

namespace Modules\Crm\Repositories\SalesInvoices;

use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Facades\DB;
use Modules\Crm\Models\SalesInvoiceDetails;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalesInvoiceDetailsRepository extends BaseEloquentRepository implements SalesInvoiceDetailsRepositoryInterface
{
    /** @var string */
    protected $modelName = SalesInvoiceDetails::class;

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
            $salesinvoiceDetails = $this->executeSave($data);

            $masterData = $this->calculateMasterData($salesinvoiceDetails->sales_invoice_id);

            $data['item']   = $salesinvoiceDetails;
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
    public function updateByInstance($salesinvoiceDetails,$data)
    {
        $this->instance = $salesinvoiceDetails;
        DB::beginTransaction();
        try {

            $data =  $this->calculation($data);
            $salesinvoiceDetails = $this->executeSave($data);

            $masterData = $this->calculateMasterData($salesinvoiceDetails->sales_invoice_id);

            $data['item']   = $salesinvoiceDetails;
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


    private function calculateMasterData($sales_invoice_id)
    {

        $items  = $this->getAllBy(['sales_invoice_id' => $sales_invoice_id],fields:['price','quantity','tax_value','discount_type','discount_value','total'])->toArray();
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

        app()->make(SalesInvoiceRepositoryInterface::class)->updateById($sales_invoice_id, $data);

        return $data;
    }


    /**
     * delete a  record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function delete($id,$sales_invoice_id)
    {
        $salesinvoiceDetails = $this->findById($id);

        if (!$salesinvoiceDetails) {
            throw new NotFoundHttpException();
        }


        DB::beginTransaction();
        try {

            $this->destroy($id);
            $masterData = $this->calculateMasterData($sales_invoice_id);

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
    public function deleteAll($ids,$sales_invoice_id)
    {

        DB::beginTransaction();
        try {

            $count = $this->destroyAll($ids);
            $masterData = $this->calculateMasterData($sales_invoice_id);

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
