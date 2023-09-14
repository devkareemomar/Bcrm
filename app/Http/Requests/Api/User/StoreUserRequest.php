<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'photo_id' => ['nullable', 'exists:core_media,id'],
            'roles_ids' => ['required', 'array'],
            'roles_ids.*' => ['numeric', 'not_in:1', 'exists:App\Models\Role,id'] // TODO: optimize
        ];
    }
}
