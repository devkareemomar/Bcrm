<?php

namespace Modules\Cms\Http\Requests\Categories;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Cms\Enums\Categories\CategoryTypeEnum;
use Modules\Cms\Rules\Categories\UniqueUpdateCategoryRule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ar' => ['required', 'string', new UniqueUpdateCategoryRule($this->route('category'))],
            'name_en' => ['required', 'string', new UniqueUpdateCategoryRule($this->route('category'))],
            'description_ar' => ['sometimes'],
            'description_en' => ['sometimes'],
            'photo_media_id' => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
            'type' => ['required', new Enum(CategoryTypeEnum::class)],
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
