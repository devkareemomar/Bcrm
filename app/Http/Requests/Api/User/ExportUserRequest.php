<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Validation\Rules\Enum;
use App\Enum\Exports\ExportFormatTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ExportUserRequest extends FormRequest
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
            'role_id' => ['nullable', 'numeric', 'min:2', 'exists:roles,id'],
            'format' => ['required', new Enum(ExportFormatTypeEnum::class)]
        ];
    }
}
