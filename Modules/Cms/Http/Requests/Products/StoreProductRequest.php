<?php

namespace Modules\Cms\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'category_id' => ['exists:Modules\Cms\Models\Category,id'],
            'photo_media_id' => ['required', 'exists:Modules\Cms\Models\Media,id'],
            'price' => ['required', 'numeric'],
            'photos_ids.*' => ['exists:Modules\Cms\Models\Media,id']
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
