<?php

namespace Modules\Cms\Http\Requests\Faqs;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_ar' => ['required', 'string'],
            'question_en' => ['required', 'string'],
            'answer_ar' => ['required', 'string'],
            'answer_en' => ['required', 'string'],
            'photo_media_id' => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
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
