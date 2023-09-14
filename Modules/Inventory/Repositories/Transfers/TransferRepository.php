<?php

namespace Modules\Inventory\Repositories\Transfers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\Transfer;
use App\Repositories\BaseEloquentRepository;
use Modules\Inventory\Repositories\Quantities\QuantityRepositoryInterface;

class TransferRepository extends BaseEloquentRepository implements TransferRepositoryInterface
{
    /** @var string */
    protected $modelName = Transfer::class;

    protected $filterableFields = [];
    protected $searchableFields = [];


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

            // process item transfers
            if(in_array($data['status'], ['approved','completed'])){
                foreach ($data['items'] as $item) {
                    $this->processTransfers($data, $item);
                }
            }


            $transfer = $this->executeSave(Arr::except($data, 'items'));

            $transferItems = collect($data['items'])->mapWithKeys(function ($item) {
                return [$item['item_id'] => ['quantity' => $item['quantity'],'unit_id'=>$item['unit_id']]];
            })->toArray();

            $transfer->items()->attach($transferItems);

            // commit changes
            DB::commit();
            return $transfer;
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
    public function updateByInstance($transfer, $data)
    {
        $this->instance = $transfer;

        DB::beginTransaction();
        try {

            // process item transfers
            if(in_array($data['status'], ['approved','completed'])){
                foreach ($data['items'] as $item) {
                    $this->processTransfers($data, $item);
                }
            }


            $transfer = $this->executeSave(Arr::except($data, 'items'));

            $transferItems = collect($data['items'])->mapWithKeys(function ($item) {
                return [$item['item_id'] => ['quantity' => $item['quantity'],'unit_id'=>$item['unit_id']]];
            })->toArray();

            $transfer->items()->sync($transferItems);

            // commit changes
            DB::commit();
            return $transfer;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * process quantities transfers from source store to destination store
     * must be called inside database transaction
     */
    private function processTransfers($data, $item)
    {
        $source = DB::table('sto_quantities')->where([
            'item_id' => $item['item_id'],
            'store_id' => $data['from_store_id']
        ])->lockForUpdate()->first();

        // update source quantity
        DB::table('sto_quantities')->where([
            'item_id' => $item['item_id'],
            'store_id' => $data['from_store_id']
        ])->update(['quantity' => $source->quantity - $item['quantity']]);

        $destination = DB::table('sto_quantities')->where([
            'item_id' => $item['item_id'],
            'store_id' => $data['to_store_id']
        ])->lockForUpdate()->first();

        $quantity = $destination ? $destination->quantity + $item['quantity'] : $item['quantity'];

        // update or create destination quantity
        DB::table('sto_quantities')->where([
            'item_id' => $item['item_id'],
            'store_id' => $data['to_store_id']
        ])->updateOrInsert([
            'item_id' => $item['item_id'],
            'store_id' => $data['to_store_id'],
        ], [
            'quantity' => $quantity
        ]);
    }

    /**
     * return max id
     */
    public function maxId()
    {
        $instance = $this->getQueryBuilder();
        $id = $instance->max('id');
        return $id ? $id + 1 : 1;
    }
}
