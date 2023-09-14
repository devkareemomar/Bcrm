<?php

namespace Modules\Inventory\Http\Requests\Stores;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'name'                => ['required', 'string'],
            'address'             => ['nullable', 'string'],
            'phone'               => ['nullable', 'string'],
            'storekeeper_user_id' => ['nullable', 'not_in:1', 'exists:users,id'],
        ];
    }
}
