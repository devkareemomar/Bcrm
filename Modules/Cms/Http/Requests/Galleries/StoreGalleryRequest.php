<?php

namespace Modules\Cms\Http\Requests\Galleries;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Cms\Enums\Galleries\GalleryFileTypeEnum;


class StoreGalleryRequest extends FormRequest
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
            'file_media_id' => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
            'type' => [new Enum(GalleryFileTypeEnum::class)],
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
