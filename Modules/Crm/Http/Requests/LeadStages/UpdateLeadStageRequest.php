<?php

namespace Modules\Crm\Http\Requests\LeadStages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadStageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:crm_lead_stages,name,' . $this->route('lead_stage')],
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
