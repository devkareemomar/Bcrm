<?php

namespace Modules\Crm\Http\Requests\PaymentTerms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\PaymentableTypeEnum;
use Modules\Crm\Enums\PaymentTypeEnum;

class StoreBulkPaymentTermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.subject'          => ['nullable','string'],
            '*.date'             => ['required','date'],
            '*.amount'           => ['required','numeric'],
            '*.payment_type'     => ['required',new Enum(PaymentTypeEnum::class)],
            '*.notes'            => ['nullable','string'],
            '*.paymentable_type' => ['required', new Enum(PaymentableTypeEnum::class)],
            '*.paymentable_id'   => ['required','numeric','exists:Modules\Crm\Models\\'.$this[0]['paymentable_type'].',id'],
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
