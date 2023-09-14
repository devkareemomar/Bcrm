<?php

namespace Modules\Crm\Http\Requests\SalesOrders;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\PaymentTypeEnum;
use Modules\Crm\Enums\SalesOrderStageEnum;
use Modules\Crm\Http\Requests\CheckItemsRequest;
use Modules\Crm\Http\Requests\InsertBulkPaymentTermRequest;
use Modules\Crm\Rules\ExistUserTeamRule;

class StoreSalesOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'subject'           => ['nullable', 'string'],
            'date'              => ['required', 'date'],
            'delivery_date'     => ['required', 'date'],
            'delivery_address'  => ['nullable', 'string'],
            'stage'             => ['required', new Enum(SalesOrderStageEnum::class)],
            'payment_type'      => ['required',  new Enum(PaymentTypeEnum::class)],
            'terms'             => ['nullable', 'string'],
            'descriptions'      => ['nullable', 'string'],

            'approved_by_id'    => ['nullable', 'numeric','exists:users,id'],
            'document_media_id' => ['nullable', 'numeric','exists:core_media,id'],
            'currency_id'       => ['nullable', 'numeric', 'exists:core_currencies,id'],
            'department_id'     => ['nullable', 'numeric', 'exists:core_departments,id'],
            'client_id'         => ['required', 'numeric', 'exists:crm_clients,id'],
            'contact_id'        => ['required',Rule::exists('crm_contacts','id')->where('client_id',$this->client_id)],
            'team_id'           => ['required', 'numeric', 'exists:core_teams,id'],
            'assign_to_id'      => ['required', 'numeric', 'exists:users,id',new ExistUserTeamRule($this->team_id)],
            'quotation_id'      => ['nullable', 'numeric', 'exists:crm_quotations,id'],
            'opportunity_id'    => ['nullable', 'numeric', 'exists:crm_opportunities,id'],

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
