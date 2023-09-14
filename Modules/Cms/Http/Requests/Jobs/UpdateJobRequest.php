<?php

namespace Modules\Cms\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ar' => ['required', 'string'],
            'name_en' => ['required', 'string'],
            'description_ar' => ['required'],
            'description_en' => ['required'],
            'photo_media_id' => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date']
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
