<?php

namespace Modules\Inventory\Http\Requests\Items;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Inventory\Enums\ItemTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Inventory\Enums\ItemStatusEnum;
use Modules\Inventory\Enums\ItemSubTypeEnum;

class StoreItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'               => ['required', 'string'],
            'color'              => ['nullable', 'string', 'max:15'],
            'size'               => ['nullable', 'string', 'max:15'],
            'item_link'          => ['nullable', 'string'],
            'description'        => ['nullable', 'string'],
            'purchase_price'     => ['required', 'numeric'],
            'sale_price'         => ['required', 'numeric'],
            'min_price'          => ['required', 'numeric'],
            'packing'            => ['nullable', 'string'],
            'packing_size'       => ['nullable', 'string'],
            'item_weight'        => ['nullable', 'string'],
            'alert_quantity'     => ['nullable', 'integer'],
            'is_active'          => ['nullable', 'boolean'],
            'item_status'        => ['required', new Enum(ItemStatusEnum::class)],
            'type'               => ['required', new Enum(ItemTypeEnum::class)],
            'sub_type'           => ['required', new Enum(ItemSubTypeEnum::class)],
            'photo_media_id'     => ['nullable', 'exists:core_media,id'],
            'tax_id'             => ['nullable', 'exists:sto_taxes,id'],
            'category_id'        => ['nullable', Rule::exists('sto_categories', 'id')->where('company_id', $this->route('company'))],
            'brand_id'           => ['nullable', Rule::exists('sto_brands', 'id')->where('company_id', $this->route('company'))],
            'unit_id'            => ['nullable', 'exists:sto_units,id'],
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
