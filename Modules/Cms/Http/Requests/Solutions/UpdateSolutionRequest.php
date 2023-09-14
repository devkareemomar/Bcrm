<?php

namespace Modules\Cms\Http\Requests\Solutions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSolutionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug_ar'             => ['required', 'unique:cms_pages,slug_ar,' . $this->route('solution')],
            'slug_en'             => ['required', 'unique:cms_pages,slug_en,' . $this->route('solution')],
            'title_ar'            => ['required', 'string'],
            'title_en'            => ['required', 'string'],
            'description_ar'      => ['sometimes'],
            'description_en'      => ['sometimes'],
            'content_ar'          => ['sometimes'],
            'content_en'          => ['sometimes'],
            'parent_id'           => ['nullable', 'numeric', 'exists:Modules\Cms\Models\Solution,id', 'not_in:' . $this->route('solution')],
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
