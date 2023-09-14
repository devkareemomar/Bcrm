<?php

namespace Modules\Inventory\Http\Resources\Items;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inventory\Http\Resources\Taxes\TaxBriefResource;
use Modules\Inventory\Http\Resources\Units\UnitBriefResource;
use Modules\Core\Http\Resources\Companies\CompanyBriefResource;
use Modules\Inventory\Http\Resources\Brands\BrandBriefResource;
use Modules\Inventory\Http\Resources\Categories\CategoryBriefResource;

class ItemResource extends JsonResource
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
            'id'                 =>     $this->id,
            'name'               =>     $this->name,
            'description'        =>     $this->description,
            'color'              =>     $this->color,
            'size'               =>     $this->size,
            'item_link'          =>     $this->item_link,
            'purchase_price'     =>     $this->purchase_price,
            'sale_price'         =>     $this->sale_price,
            'min_price'          =>     $this->min_price,
            'packing'            =>     $this->packing,
            'packing_size'       =>     $this->packing_size,
            'item_weight'        =>     $this->item_weight,
            'alert_quantity'     =>     $this->alert_quantity,
            'is_active'          =>     $this->is_active,
            'item_status'        =>     $this->item_status,
            'type'               =>     $this->type,
            'sub_type'           =>     $this->sub_type,
            'photo'              =>     isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'tax'                =>     new TaxBriefResource($this->whenLoaded('tax')),
            'category'           =>     new CategoryBriefResource($this->whenLoaded('category')),
            'brand'              =>     new BrandBriefResource($this->whenLoaded('brand')),
            'unit'               =>     new UnitBriefResource($this->whenLoaded('unit')),
        ];
    }
}
