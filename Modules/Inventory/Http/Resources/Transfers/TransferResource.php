<?php

namespace Modules\Inventory\Http\Resources\Transfers;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Inventory\Http\Resources\Stores\StoreBriefResource;

class TransferResource extends JsonResource
{
    /**
     * Transform user resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'date'           => $this->date,
            'total_quantity' => $this->total_quantity,
            'items_count'    => $this->items_count,
            'notes'          => $this->notes,
            'status'         => $this->status,
            'document'       => isset($this->document) ? asset(Storage::url($this->document->file)) : null,
            'user'           => new BriefUserResource($this->whenLoaded('user')),
            'from_store'     => new StoreBriefResource($this->whenLoaded('fromStore')),
            'to_store'       => new StoreBriefResource($this->whenLoaded('toStore')),
            'items'          => TransferItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
