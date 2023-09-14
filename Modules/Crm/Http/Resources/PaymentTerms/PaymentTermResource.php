<?php

namespace Modules\Crm\Http\Resources\PaymentTerms;


use Illuminate\Http\Resources\Json\JsonResource;


class PaymentTermResource extends JsonResource
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
        $pieces = explode('\\', $this->paymentable_type);
        $module = array_pop($pieces);

        return [
            'id'               =>  $this->id,
            'subject'          =>  $this->subject,
            'date'             =>  $this->date,
            'amount'           =>  $this->amount,
            'payment_type'     =>  $this->payment_type,
            'notes'            =>  $this->notes,
            'paymentable_id'   =>  $this->paymentable->id,
            'paymentable_type' =>  $module,
            'paymentable_code'   =>  $this->paymentable->code,

        ];
    }
}
