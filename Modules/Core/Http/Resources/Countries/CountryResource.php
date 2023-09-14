<?php

namespace Modules\Core\Http\Resources\Countries;


use Illuminate\Http\Resources\Json\JsonResource;


class CountryResource extends JsonResource
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
            'code' => $this->code,
            'phone_code' => $this->phone_code,
        ];
    }
}