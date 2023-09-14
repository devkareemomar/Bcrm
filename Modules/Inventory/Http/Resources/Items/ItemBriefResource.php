<?php

namespace Modules\Inventory\Http\Resources\Items;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemBriefResource extends JsonResource
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
            'id'         =>    $this->id,
            'name'       =>    $this->name,
            'sale_price' =>    $this->sale_price,
        ];
    }
}
