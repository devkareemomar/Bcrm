<?php

namespace Modules\Crm\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\DiscountTypeEnum;

class CheckItemsRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
    {

        return [
            'items.*.item_id'         => ['required', 'exists:sto_items,id'],
            'items.*.quantity'        => ['required', 'numeric'],
            'items.*.price'           => ['required', 'numeric'],
            'items.*.unit_id'         => ['required', 'exists:sto_units,id'],
            'items.*.tax_id'          => ['nullable', 'exists:sto_taxes,id'],
            'items.*.tax_rate'        => ['nullable', 'numeric'],
            'items.*.tax_value'       => ['nullable', 'numeric'],
            'items.*.discount_type'   => ['nullable', new Enum(DiscountTypeEnum::class)],
            'items.*.discount_value'  => ['nullable', 'numeric'],
        ];
    }
}
