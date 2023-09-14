<?php

namespace Modules\Crm\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\PaymentableTypeEnum;
use Modules\Crm\Enums\PaymentTypeEnum;

class UpdatePaymentTermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'          => ['nullable','string'],
            'date'             => ['required','date'],
            'amount'           => ['required','numeric'],
            'payment_type'     => ['required',new Enum(PaymentTypeEnum::class)],
            'notes'            => ['nullable','string'],
            'paymentable_id'   => ['required','numeric','exists:Modules\Crm\Models\\'.$this->paymentable_type.',id'],
            'paymentable_type' => ['required', new Enum(PaymentableTypeEnum::class)],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
