<?php

namespace Modules\Crm\Http\Requests\SalesInvoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\PaymentTypeEnum;
use Modules\Crm\Enums\SalesInvoiceStageEnum;
use Modules\Crm\Http\Requests\CheckItemsRequest;
use Modules\Crm\Rules\ExistUserTeamRule;

class StoreSalesInvoiceRequest extends FormRequest
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
            'due_date_payment'  => ['nullable', 'date'],
            'delivery_date'     => ['required', 'date'],
            'delivery_address'  => ['nullable', 'string'],
            'stage'             => ['required', new Enum(SalesInvoiceStageEnum::class)],
            'payment_type'      => ['required',  new Enum(PaymentTypeEnum::class)],
            'terms'             => ['nullable', 'string'],
            'notes'             => ['nullable', 'string'],

            'approved_by_id'    => ['nullable', 'numeric', 'exists:users,id'],
            'document_media_id' => ['nullable', 'numeric', 'exists:core_media,id'],
            'currency_id'       => ['nullable', 'numeric', 'exists:core_currencies,id'],
            'department_id'     => ['nullable', 'numeric', 'exists:core_departments,id'],
            'client_id'         => ['required', 'numeric', 'exists:crm_clients,id'],
            'contact_id'        => ['required',Rule::exists('crm_contacts','id')->where('contactable_id',$this->client_id)->where('contactable_type','Modules\Crm\Models\Client')],
            'team_id'           => ['required', 'numeric', 'exists:core_teams,id'],
            'assign_to_id'      => ['required', 'numeric', 'exists:users,id',new ExistUserTeamRule($this->team_id)],
            'quotation_id'      => ['nullable', 'numeric', 'exists:crm_quotations,id'],
            'opportunity_id'    => ['nullable', 'numeric', 'exists:crm_opportunities,id'],
            'sales_order_id'    => ['nullable', 'numeric', 'exists:crm_sales_orders,id'],
            'items'             => ['required', 'array','min:1'],
        ];

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
