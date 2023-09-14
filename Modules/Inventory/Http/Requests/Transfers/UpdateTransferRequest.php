<?php

namespace Modules\Inventory\Http\Requests\Transfers;

use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Inventory\Enums\TransferStatusEnum;
use Modules\Inventory\Repositories\Quantities\QuantityRepositoryInterface;

class UpdateTransferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'              => ['required', 'date'],
            'status'            => ['required', new Enum(TransferStatusEnum::class)],
            'notes'             => ['nullable', 'string'],
            'document_media_id' => ['nullable','numeric',Rule::exists('core_media', 'id')],
            'from_store_id'     => ['required', Rule::exists('sto_stores', 'id')],
            'to_store_id'       => ['required', 'different:from_store_id', Rule::exists('sto_stores', 'id')],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.quantity'  => ['required', 'numeric'],
            'items.*.unit_id'   => ['required', 'numeric', Rule::exists('sto_units', 'id')],
            'items.*.item_id'   => ['required', 'numeric', 'distinct']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            try {
                $this->isValidTransfer($validator->validated());
            } catch (\Throwable $th) {
                $validator->errors()->add('items', $th->getMessage());
            }
        });
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

    private function isValidTransfer($data)
    {
        $items = collect($data['items']);
        $itemsIds = $items->pluck('item_id')->toArray();

        $quantites = app(QuantityRepositoryInterface::class)->getAllWhereIn($itemsIds, whereInField: 'item_id', parameters: ['store_id' => $data['from_store_id']]);

        if ($quantites->count() != $items->count()) {
            throw new Exception('invalid item_ids.');
        }

        $items = $items->keyBy('item_id');
        foreach ($quantites as  $quantity) {
            if ($items[$quantity->item_id]['quantity'] > $quantity->quantity) {
                throw new Exception('invalid item quantity.');
            }
        }
    }
}
