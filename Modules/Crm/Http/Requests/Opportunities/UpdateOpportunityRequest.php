<?php

namespace Modules\Crm\Http\Requests\Opportunities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\OpportunityStageEnum;
use Modules\Crm\Enums\PaymentTypeEnum;
use Modules\Crm\Rules\ExistUserTeamRule;

class UpdateOpportunityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'              => ['required', 'date'],
            'subject'           => ['required', 'string'],
            'stage'             => ['required', new Enum(OpportunityStageEnum::class)],
            'payment_type'      => ['required', new Enum(PaymentTypeEnum::class)],
            'probability'       => ['nullable', 'numeric'],
            'description'       => ['nullable', 'string'],
            'terms'             => ['nullable', 'string'],
            'document_media_id' => ['nullable', 'exists:core_media,id'],
            'currency_id'       => ['nullable', 'exists:core_currencies,id'],
            'lead_id'           => ['required', 'numeric', 'exists:crm_leads,id'],
            'department_id'     => ['required', 'numeric', 'exists:core_departments,id'],
            'team_id'           => ['required', 'numeric', 'exists:core_teams,id'],
            'source_id'         => ['nullable', 'numeric','exists:crm_sources,id'],
            'assign_to_id'      => ['required', 'numeric', 'exists:users,id', new ExistUserTeamRule($this->team_id)],
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
