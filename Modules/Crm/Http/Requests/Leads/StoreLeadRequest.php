<?php

namespace Modules\Crm\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\LeadClassEnum;
use Modules\Crm\Enums\LeadOrClientTypeEnum;
use Modules\Crm\Enums\LeadTypeEnum;

class StoreLeadRequest extends FormRequest
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
            'type'                => ['required',new Enum(LeadTypeEnum::class)],
            'company_name'        => ['nullable','string'],
            'contact_person'      => ['nullable','string'],
            'first_name'          => ['required','string'],
            'last_name'           => ['required','string'],
            'phone'               => ['required','string'],
            'mobile'              => ['nullable','string'],
            'email'               => ['required','string' ,'email', 'unique:crm_leads,email'],
            'address'             => ['nullable','string'],
            'location'            => ['nullable','string'],
            'city'                => ['required','string'],
            'country'             => ['nullable','string'],
            'website'             => ['nullable','string'],
            'facebook'            => ['nullable','string'],
            'linkedin'            => ['nullable','string'],
            'industry'            => ['nullable','string'],
            'longitude'           => ['nullable','string'],
            'latitude'            => ['nullable','string'],
            'number_of_employees' => ['nullable','string'],
            'details'             => ['nullable','string'],
            'department_id'       => ['nullable','exists:core_departments,id'],
            'assign_to_id'        => ['required','exists:users,id'],
            'team_id'             => ['nullable','exists:core_teams,id'],
            'stage_id'            => ['required','exists:crm_lead_stages,id'],
            'source_id'           => ['required', 'numeric','exists:crm_sources,id'],
            'photo_id'            => ['nullable', 'exists:core_media,id'],
            'class_id'            => ['required','exists:core_classes,id'],

            'documents'                     => ['nullable','array'],
            'documents.*.lead_id'           => ['nullable','exists:crm_leads,id'],
            'documents.*.document_media_id' => ['nullable','exists:core_media,id'],
            'documents.*.expire_date'       => ['nullable','date'],
        ];
    }



}
