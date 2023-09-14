<?php

namespace Modules\Crm\Http\Resources\Leads;

use Illuminate\Http\Resources\Json\JsonResource;


class LeadBriefResource extends JsonResource
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
            "id"      => $this->id,
            'code'    => $this->code,
            'name'    => $this->first_name .' '.$this->last_name,
            'phone'   => $this->phone,
            'email'   => $this->email,
            'address' => $this->address,
        ];
    }
}
