<?php

namespace Modules\Inventory\Http\Resources\Quantities;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inventory\Http\Resources\Items\ItemBriefResource;
use Modules\Inventory\Http\Resources\Stores\StoreBriefResource;

class QuantityResource extends JsonResource
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
            'id'         => $this->id,
            'quantity'        => $this->quantity,
            'item'       => new ItemBriefResource($this->whenLoaded('item')),
        ];
    }
}
