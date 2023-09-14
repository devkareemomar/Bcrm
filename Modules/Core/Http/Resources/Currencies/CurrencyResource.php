<?php

namespace Modules\Core\Http\Resources\Currencies;


use Illuminate\Http\Resources\Json\JsonResource;


class CurrencyResource extends JsonResource
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
            'name'    => $this->name,
            'symbol'  => $this->symbol,
            'code'    => $this->code,
        ];
    }
}
