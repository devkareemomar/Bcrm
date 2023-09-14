<?php

namespace Modules\Core\Http\Requests\Currencies;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    =>['required' ,'unique:core_currencies,name'],
            'symbol'  =>['required' ,'unique:core_currencies,symbol'],
            'code'    =>['required' ,'unique:core_currencies,code'],
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
