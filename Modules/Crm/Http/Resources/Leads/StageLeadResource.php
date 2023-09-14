<?php

namespace Modules\Crm\Http\Resources\Leads;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Crm\Http\Resources\Leads\LeadResource as LeadsLeadResource;

class StageLeadResource extends JsonResource
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
            "id"    => $this->id,
            'name'  => $this->name,
            'leads' => LeadsLeadResource::collection($this->leads),


        ];
    }
}
