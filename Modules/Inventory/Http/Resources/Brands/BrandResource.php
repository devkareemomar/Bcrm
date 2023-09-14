<?php

namespace Modules\Inventory\Http\Resources\Brands;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Companies\CompanyBriefResource;

class BrandResource extends JsonResource
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
            "id"                  => $this->id,
            'name'                => $this->name,
        ];
    }
}
