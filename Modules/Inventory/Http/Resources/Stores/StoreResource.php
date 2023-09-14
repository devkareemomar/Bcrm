<?php

namespace Modules\Inventory\Http\Resources\Stores;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform Lead Stage resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"                  => $this->id,
            'name'                => $this->name,
            'address'             => $this->address,
            'phone'               => $this->phone,
            'storekeeper' => new BriefUserResource($this->storekeeper),
        ];
    }
}
