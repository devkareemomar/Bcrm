<?php

namespace Modules\Core\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Media\ValidFileNameRule;

class StoreMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->file);

        return [
            "file"  => ['required', 'file', 'max:' . config('core.max_media_file_size')],
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
