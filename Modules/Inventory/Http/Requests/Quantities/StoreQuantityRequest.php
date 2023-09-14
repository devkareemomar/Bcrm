<?php

namespace Modules\Inventory\Http\Requests\Quantities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuantityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id'   => ['required', Rule::unique('sto_quantities', 'item_id')->where('store_id', $this->route('store'))],
            'quantity'  => ['required', 'numeric'],
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
