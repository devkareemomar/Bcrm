<?php

namespace Modules\Crm\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\DiscountTypeEnum;


class UpdateQuotationDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id'         => ['required','exists:sto_items,id',Rule::unique('crm_quotation_details','item_id')->ignore($this->route('detail'),'id')->where('quotation_id' , $this->route('quotation_id'))],
            'unit_id'         => ['required', 'exists:sto_units,id'],
            'quantity'        => ['required','numeric'],
            'price'           => ['required','numeric'],
            'tax_id'          => ['nullable','exists:sto_taxes,id'],
            'tax_rate'        => ['nullable','numeric'],
            'tax_value'       => ['nullable','numeric'],
            'discount_type'   => ['nullable', new Enum(DiscountTypeEnum::class)],
            'discount_value'  => ['nullable','numeric'],
            'total'           => ['nullable','numeric'],
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
