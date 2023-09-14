<?php

namespace Modules\Inventory\Http\Resources\Items;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PaginatedCollection;

class ItemCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id'                 =>     $item->id,
            'name'               =>     $item->name,
            'purchase_price'     =>     $item->purchase_price,
            'sale_price'         =>     $item->sale_price,
            'min_price'          =>     $item->min_price,
            'alert_quantity'          =>     $item->alert_quantity,
            'is_active'          =>     $item->is_active,
            'item_status'        =>     $item->item_status,
            'type'               =>     $item->type,
            'sub_type'           =>     $item->sub_type,
            'photo'              =>     isset($item->photo) ? asset(Storage::url($item->photo->file)) : null,
        ];
    }
}
