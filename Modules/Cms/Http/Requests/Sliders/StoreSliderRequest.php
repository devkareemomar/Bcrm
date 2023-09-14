<?php

namespace Modules\Cms\Http\Requests\Sliders;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_ar' => ['string'],
            'title_en' => ['string'],
            'category_id' => ['exists:Modules\Cms\Models\Category,id'],
            'description_ar' => ['sometimes'],
            'description_en' => ['sometimes'],
            'photo_media_id'      => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
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
