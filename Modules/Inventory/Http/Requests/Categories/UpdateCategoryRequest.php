<?php

namespace Modules\Inventory\Http\Requests\Categories;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => [
                'required', 'string',
                Rule::unique('sto_categories', 'name')
                    ->ignore($this->route('category'), 'id')->where('company_id', $this->route('company'))
            ],
            'parent_id'  => [
                'nullable', 'integer',
                Rule::exists('sto_categories', 'id')->where('company_id', $this->route('company'))
            ],
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
