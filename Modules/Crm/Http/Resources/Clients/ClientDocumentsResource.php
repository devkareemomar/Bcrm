<?php

namespace Modules\Crm\Http\Resources\Clients;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class ClientDocumentsResource extends JsonResource
{
    /**
     * Transform Lead Stage resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"                  => $this->pivot->id,
            "document_media_id"   => $this->pivot->document_media_id,
            'document'            => isset($this->file) ? asset(Storage::url($this->file)) : null,
            'expire_date'         => $this->pivot->expire_date,
        ];
    }
}
