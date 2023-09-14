<?php

namespace Modules\Crm\Http\Requests\Opportunities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\OpportunityStageEnum;
use Modules\Crm\Http\Requests\CheckItemsRequest;
use Illuminate\Validation\Rule;
use Modules\Crm\Enums\PaymentTypeEnum;
use Modules\Crm\Http\Requests\InsertBulkPaymentTermRequest;
use Modules\Crm\Rules\ExistUserTeamRule;

class StoreOpportunityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
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
            'assign_to_id'      => ['required', 'numeric', 'exists:users,id',new ExistUserTeamRule($this->team_id)],
            'source_id'         => ['nullable', 'numeric','exists:crm_sources,id'],
            'payment_terms'     => ['required', 'array', 'min:1'],
            'items'             => ['required', 'array', 'min:1'],
        ];

        $rules +=  InsertBulkPaymentTermRequest::rules();
        $rules +=  CheckItemsRequest::rules();

        return $rules;
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
