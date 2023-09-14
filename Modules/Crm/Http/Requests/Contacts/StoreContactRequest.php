<?php

namespace Modules\Crm\Http\Requests\Contacts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\ContactableTypeEnum;

class StoreContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'       => ['required','string'],
            'last_name'        => ['required','string'],
            'phone'            => ['nullable','string'],
            'mobile'           => ['nullable','string'],
            'email'            => ['nullable','string','email'],
            'address'          => ['nullable','string'],
            'position'         => ['nullable','string'],
            'client_id'        => ['nullable', 'exists:crm_clients,id'],
            'lead_id'          => ['nullable', 'exists:crm_leads,id'],
            'photo_id'         => ['nullable', 'exists:core_media,id'],
            'city_id'          => ['nullable', 'exists:core_cities,id'],
            'country_id'       => ['nullable', 'exists:core_countries,id'],

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
