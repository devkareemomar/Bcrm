<?php

namespace Modules\Cms\Http\Requests\Setting;


use Illuminate\Foundation\Http\FormRequest;

class CreateSeoSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'site_map'           => ['nullable','file','max:' . config('cms.max_media_file_size')],
            'robot'              => ['nullable', 'file'],'max:' . config('cms.max_media_file_size'),
            'custom_code_head'   => ['nullable'],
            'custom_code_footer' => ['nullable'],
            'custom_code_body'   => ['nullable']
        ];
    }
}
