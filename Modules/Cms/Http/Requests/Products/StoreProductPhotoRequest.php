<?php

namespace Modules\Cms\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductPhotoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photos_media_ids' => ['required', 'array', 'min:1'],
            'photos_media_ids.*' => ['required', 'exists:Modules\Cms\Models\Media,id'], // FIXME: maybe query for each id can be solved with IN clause
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
