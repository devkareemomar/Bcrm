<?php

namespace Modules\Crm\Http\Requests\Quotations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\PaymentTypeEnum;
use Modules\Crm\Enums\QuotationStageEnum;
use Modules\Crm\Http\Requests\CheckItemsRequest;
use Modules\Crm\Rules\ExistUserTeamRule;
use Illuminate\Validation\Rule;
use Modules\Crm\Http\Requests\InsertBulkPaymentTermRequest;

class StoreQuotationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'subject'           => ['required', 'string'],
            'start_date'        => ['required', 'date'],
            'end_date'          => ['required', 'date'],
            'stage'             => ['required', new Enum(QuotationStageEnum::class)],
            'client_id'         => ['required', 'numeric', 'exists:crm_clients,id'],
            'contact_id'        => ['required',Rule::exists('crm_contacts','id')->where('client_id',$this->client_id)],
            'team_id'           => ['required', 'numeric', 'exists:core_teams,id'],
            'assign_to_id'      => ['required', 'numeric', 'exists:users,id',new ExistUserTeamRule($this->team_id)],
            'currency_id'       => ['nullable', 'exists:core_currencies,id'],
            'payment_type'      => ['required', new Enum(PaymentTypeEnum::class)],
            'terms'             => ['nullable', 'string'],
            'notes'             => ['nullable', 'string'],
            'document_media_id' => ['nullable', 'numeric','exists:core_media,id'],
            'department_id'     => ['required', 'numeric', 'exists:core_departments,id'],

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
