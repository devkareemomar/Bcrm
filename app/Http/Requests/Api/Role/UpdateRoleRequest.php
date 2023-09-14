<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:roles,name,' . $this->route('role')],
            'display_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'permissions' => ['array', 'min:1'],
            'permissions.*' => ['required', 'string']
        ];
    }

    protected function prepareForValidation()
    {
        // replace name value
        $this->merge(['name' => str_replace(' ', '_', strtolower(strtolower(trim(request()->input('display_name')))))]);
    }
}
