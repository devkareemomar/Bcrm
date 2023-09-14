<?php

namespace Modules\Cms\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Cms\Enums\Posts\PostTypeEnum;

class StorePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug_ar'             => ['required', 'unique:cms_posts,slug_ar'],
            'slug_en'             => ['required', 'unique:cms_posts,slug_en'],
            'title_ar'            => ['required', 'string'],
            'title_en'            => ['required', 'string'],
            'description_ar'      => ['required'],
            'description_en'      => ['required'],
            'photo_media_id'      => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
            'category_id'         => ['exists:Modules\Cms\Models\Category,id'],
            'type'                => ['required', new Enum(PostTypeEnum::class)],
            'author'              => ['required', 'string'],
            'is_active'           => ['required', 'bool'],
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
