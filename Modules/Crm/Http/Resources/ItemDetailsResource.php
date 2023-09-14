<?php

namespace Modules\Crm\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inventory\Http\Resources\Items\ItemBriefResource;
use Modules\Inventory\Http\Resources\Taxes\TaxBriefResource;
use Modules\Inventory\Http\Resources\Units\UnitBriefResource;

class ItemDetailsResource extends JsonResource
{
    /**
     * Transform user resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'             =>   $this->id,
            'item'           =>   new ItemBriefResource($this->whenLoaded('item')),
            'unit'           =>   new UnitBriefResource($this->whenLoaded('unit')),
            'quantity'       =>   $this->quantity,
            'price'          =>   $this->price,
            'tax'            =>   new TaxBriefResource($this->whenLoaded('tax')),
            'tax_rate'       =>   $this->tax_rate,
            'tax_value'      =>   $this->tax_value,
            'discount_type'  =>   $this->discount_type,
            'discount_value' =>   $this->discount_value,
            'total'          =>   $this->total,

        ];
    }
}
