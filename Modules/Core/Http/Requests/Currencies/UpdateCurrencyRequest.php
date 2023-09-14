<?php

namespace Modules\Core\Http\Requests\Currencies;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    =>['required' ,'unique:core_currencies,name,'  .$this->route('currency')],
            'symbol'  =>['required' ,'unique:core_currencies,symbol,'.$this->route('currency')],
            'code'    =>['required' ,'unique:core_currencies,code,'  .$this->route('currency')],
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
