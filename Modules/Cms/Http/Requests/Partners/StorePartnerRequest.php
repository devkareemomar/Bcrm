<?php

namespace Modules\Cms\Http\Requests\Partners;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['exists:Modules\Cms\Models\Category,id'],
            'name' => ['required', 'string'],
            'title' => ['string'],
            'opinion' => ['string'],
            'evaluation' => ['numeric', 'max:255'],
            'logo_media_id' => ['nullable', 'exists:Modules\Cms\Models\Media,id'],
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
