<?php

namespace Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\PaymentTypeEnum;

class InsertBulkPaymentTermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'payment_terms.*.subject'          => ['required','string'],
            'payment_terms.*.date'             => ['required','date'],
            'payment_terms.*.amount'           => ['required','numeric'],
            'payment_terms.*.payment_type'     => ['required',new Enum(PaymentTypeEnum::class)],
            'payment_terms.*.notes'            => ['required','string'],
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
