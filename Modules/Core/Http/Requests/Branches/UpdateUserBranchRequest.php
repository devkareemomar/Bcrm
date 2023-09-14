<?php

namespace Modules\Core\Http\Requests\Branches;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserBranchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'branches_ids' => ['required', 'array'],
            'branches_ids.*' => ['exists:Modules\Core\Models\Branch,id']
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
