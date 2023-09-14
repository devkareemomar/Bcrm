<?php

namespace Modules\Core\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserTeamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teams_ids' => ['required', 'array'],
            'teams_ids.*' => ['exists:Modules\Core\Models\Team,id']
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
