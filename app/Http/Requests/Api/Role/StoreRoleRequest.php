<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('super_admin');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'display_name' => ['required', 'string'],
            'description' => ['string'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', 'string']
        ];
    }


    protected function prepareForValidation()
    {
        // replace name value
        $this->merge(['name' => str_replace(' ', '_', strtolower(strtolower(trim(request()->input('display_name')))))]);
    }
}
