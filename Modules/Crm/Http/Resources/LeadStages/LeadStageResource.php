<?php

namespace Modules\Crm\Http\Resources\LeadStages;

use Illuminate\Http\Resources\Json\JsonResource;


class LeadStageResource extends JsonResource
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
            'id'      => $this->id,
            'name'   => $this->name,
        ];
    }
}
