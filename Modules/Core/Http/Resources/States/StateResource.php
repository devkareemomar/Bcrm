<?php

namespace Modules\Core\Http\Resources\States;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Countries\CountryBriefResource;

class StateResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'country' => new CountryBriefResource($this->country)
        ];
    }
}
