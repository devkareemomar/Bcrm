<?php

namespace Modules\Crm\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;


class BulkUpdateStageLeadRequest extends FormRequest
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
            '*.id'        => ['required', 'numeric','exists:crm_leads,id'],
            '*.stage_id'  => ['required', 'numeric','exists:crm_lead_stages,id'],
            '*.order_by'  => ['required', 'numeric'],
        ];
    }


}