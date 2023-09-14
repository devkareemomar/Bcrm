<?php

namespace Modules\Crm\Http\Resources\SalesOrders;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderBriefResource extends JsonResource
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
            'code'    => $this->code,
            'subject' => $this->subject,

        ];
    }
}
