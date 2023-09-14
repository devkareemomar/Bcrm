<?php

namespace Modules\Crm\Http\Resources\Clients;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;
use Modules\Core\Http\Resources\Classes\ClassBriefResource;
use Modules\Crm\Http\Resources\Contacts\ContactBriefResource;
use Modules\Crm\Http\Resources\Contacts\ContactResource;
use Modules\Crm\Http\Resources\Sources\SourceResource;

class ClientResource extends JsonResource
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
            'id'                => $this->id,
            'code'              => $this->code,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'company_name'      => $this->company_name,
            'contact_person'    => $this->first_name.' '.$this->last_name,
            'phone'             => $this->phone,
            'mobile'            => $this->mobile,
            'email'             => $this->email,
            'address'           => $this->address,
            'location'          => $this->location,
            'city'              => $this->city,
            'country'           => $this->country,
            'website'           => $this->website,
            'facebook'          => $this->facebook,
            'linkedin'          => $this->linkedin,
            'industry'          => $this->industry,
            'longitude'         => $this->longitude,
            'latitude'          => $this->latitude,
            'account_size'      => $this->account_size,
            'type'              => $this->type,

            'class'               => new ClassBriefResource($this->class),
            'source'            => new SourceResource($this->whenLoaded('source')),
            'branch'            => new BranchBriefResource($this->whenLoaded('branch')),
            'photo'             => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'documents'         => ClientDocumentsResource::collection($this->documents),

        ];
    }
}
