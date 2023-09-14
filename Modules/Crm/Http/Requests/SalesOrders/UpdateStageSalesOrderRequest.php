<?php

namespace Modules\Crm\Http\Requests\SalesOrders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Crm\Enums\SalesOrderStageEnum;

class UpdateStageSalesOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stage'    => ['required', new Enum(SalesOrderStageEnum::class)]
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
