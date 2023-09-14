<?php

namespace Modules\Crm\Http\Resources\PaymentTerms;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTermBriefResource extends JsonResource
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
            'id'               =>  $this->id,
            'subject'          =>  $this->subject,
            'date'             =>  $this->date,
        ];
    }
}
