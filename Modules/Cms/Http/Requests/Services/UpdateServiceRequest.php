<?php

namespace Modules\Cms\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug_ar'             => ['required', 'unique:cms_pages,slug_ar,' . $this->route('service')],
            'slug_en'             => ['required', 'unique:cms_pages,slug_en,' . $this->route('service')],
            'title_ar'            => ['required', 'string'],
            'title_en'            => ['required', 'string'],
            'category_id'         => ['exists:Modules\Cms\Models\Category,id'],
            'description_ar'      => ['sometimes'],
            'description_en'      => ['sometimes'],
            'content_ar'          => ['sometimes'],
            'content_en'          => ['sometimes'],
            'icon_media_id'       => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
            'photo_media_id'      => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
            'meta_title_ar'       => ['nullable', 'string'],
            'meta_title_en'       => ['nullable', 'string'],
            'meta_keyword_ar'     => ['nullable', 'string'],
            'meta_keyword_en'     => ['nullable', 'string'],
            'meta_description_ar' => ['nullable', 'string'],
            'meta_description_en' => ['nullable', 'string'],
            'custom_code_head'    => ['nullable', 'string'],
            'og_type'             => ['nullable', 'string'],
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
