<?php

namespace Modules\Core\Http\Requests\Branches;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'address' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
            'description' => ['nullable'],
            'commercial_register' => ['nullable'],

            'logo_media_id' => ['nullable', 'exists:Modules\Core\Models\Media,id'],
            'header_media_id' => ['nullable', 'exists:Modules\Core\Models\Media,id'],
            'footer_media_id' => ['nullable', 'exists:Modules\Core\Models\Media,id'],
            'stamp_media_id' => ['nullable', 'exists:Modules\Core\Models\Media,id'],
            'signature_media_id' => ['nullable', 'exists:Modules\Core\Models\Media,id'],
            'document_media_id' => ['nullable', 'exists:Modules\Core\Models\Media,id'],

            'city_id' => ['nullable'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('super_admin');
    }
}
