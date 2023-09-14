<?php

namespace Modules\Cms\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Cms\Rules\Media\ValidFileNameRule;

class UpdateMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ['required', new ValidFileNameRule, 'unique:cms_media,name,' . $this->route('medium')],
            "description"  => ['nullable'],
            "title" => ['nullable'],
            "alt" => ['nullable'],
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
