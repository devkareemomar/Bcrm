<?php

namespace Modules\Inventory\Http\Resources\Transfers;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferBriefResource extends JsonResource
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
            'id'    => $this->id,
            'code'  => $this->code,
            'date'  => $this->date,
            'from_to' => $this->fromStore->name . '=>' . $this->fromStore->to
        ];
    }
}
