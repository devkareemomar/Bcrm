<?php

namespace Modules\Inventory\Http\Resources\Transfers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inventory\Http\Resources\Units\UnitBriefResource;

class TransferItemResource extends JsonResource
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
            'id'           => $this->pivot->id,
            'item_id'      => $this->id,
            'item_name'    => $this->name,
            'unit'         => new UnitBriefResource($this->pivot->unit),
            'quantity'     => $this->pivot->quantity,
        ];
    }
}
