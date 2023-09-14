<?php

namespace Modules\Crm\Http\Requests\Activities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\ActivitableTypeEnum;
use Modules\Crm\Enums\ActivitTypeEnum;

class UpdateActivityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'                => ['required','date'],
            'reminder_date'       => ['required','date'],
            'type'                => ['required',new Enum(ActivitTypeEnum::class)],
            'description'         => ['nullable','string'],
            'activitable_id'      => ['required','numeric','exists:Modules\Crm\Models\\'.$this->activitable_type.',id'],
            'activitable_type'    => ['required',new Enum(ActivitableTypeEnum::class)],
            'document_media_id'   => ['nullable','numeric','exists:core_media,id'],
            'assign_to_id'        => ['required','numeric','not_in:1','exists:users,id'],
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
