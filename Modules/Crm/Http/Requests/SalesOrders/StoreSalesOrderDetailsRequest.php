<?php

namespace Modules\Crm\Http\Requests\SalesOrders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\DiscountTypeEnum;

class StoreSalesOrderDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return[
            'item_id'         => ['required','exists:sto_items,id',Rule::unique('crm_sales_order_details','item_id')->where('sales_order_id' , $this->route('sales_order_id'))],
            'unit_id'         => ['required', 'exists:sto_units,id'],
            'quantity'        => ['required', 'numeric'],
            'price'           => ['required', 'numeric'],
            'tax_id'          => ['nullable', 'exists:sto_taxes,id'],
            'tax_rate'        => ['nullable', 'numeric'],
            'tax_value'       => ['nullable', 'numeric'],
            'discount_type'   => ['nullable',  new Enum(DiscountTypeEnum::class)],
            'discount_value'  => ['nullable', 'numeric'],
            'total'           => ['nullable', 'numeric'],
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
