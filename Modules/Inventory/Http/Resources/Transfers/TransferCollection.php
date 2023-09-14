<?php

namespace Modules\Inventory\Http\Resources\Transfers;

use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Users\BriefUserResource;
use Modules\Inventory\Http\Resources\Stores\StoreBriefResource;

class TransferCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id'             => $item->id,
            'code'           => $item->code,
            'date'           => $item->date,
            'total_quantity' => $item->total_quantity,
            'items_count'    => $item->items_count,
            'status'         => $item->status,
            'user'           => new BriefUserResource($item->user),
            'from_store'     => new StoreBriefResource($item->fromStore),
            'to_store'       => new StoreBriefResource($item->toStore),
        ];
    }
}
