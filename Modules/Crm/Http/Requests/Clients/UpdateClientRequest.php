<?php

namespace Modules\Crm\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\ClientClassEnum;
use Modules\Crm\Enums\ClientTypeEnum;

class UpdateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'        => ['required','string'],
            'last_name'         => ['required','string'],
            'company_name'      => ['nullable','string'],
            'phone'             => ['required','string'],
            'mobile'            => ['nullable','string'],
            'email'             => ['required','string' ,'email', 'unique:crm_clients,email,'.$this->route('client')],
            'address'           => ['nullable','string'],
            'location'          => ['nullable','string'],
            'city'              => ['nullable','string'],
            'country'           => ['nullable','string'],
            'website'           => ['nullable','string'],
            'facebook'          => ['nullable','string'],
            'linkedin'          => ['nullable','string'],
            'industry'          => ['nullable','string'],
            'longitude'         => ['nullable','string'],
            'latitude'          => ['nullable','string'],
            'account_size'      => ['nullable','string'],
            'type'              => ['required',new Enum(ClientTypeEnum::class)],
            'source_id'         => ['nullable', 'numeric','exists:crm_sources,id'],
            'photo_id'          => ['nullable', 'exists:core_media,id'],
            'class_id'          => ['required','exists:core_classes,id'],

            'contact_email'     => ['nullable','string'],
            'contact_phone'     => ['nullable','string'],
            'contact_position'  => ['nullable','string'],

            'documents'                     => ['nullable','array'],
            'documents.*.client_id'         => ['nullable','exists:crm_clients,id'],
            'documents.*.document_media_id' => ['nullable','exists:core_media,id'],
            'documents.*.expire_date'       => ['nullable','date'],
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
