<?php

namespace Modules\Core\Http\Resources\Branches;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Cities\CityBriefResource;
use Modules\Core\Http\Resources\Companies\CompanyBriefResource;

class BranchResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'description' => $this->description,
            'logo' => isset($this->logo) ? asset(Storage::url($this->logo->file)) : null,
            'header' => isset($this->header) ? asset(Storage::url($this->header->file)) : null,
            'footer' => isset($this->footer) ? asset(Storage::url($this->footer->file)) : null,
            'stamp' => isset($this->stamp) ? asset(Storage::url($this->stamp->file)) : null,
            'signature' => isset($this->signature) ? asset(Storage::url($this->signature->file)) : null,
            'document' => isset($this->document) ? asset(Storage::url($this->document->file)) : null,
            'company' => new CompanyBriefResource($this->company),
            'city' => new CityBriefResource($this->city),
        ];
    }
}
