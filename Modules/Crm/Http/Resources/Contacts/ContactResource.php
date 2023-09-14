<?php

namespace Modules\Crm\Http\Resources\Contacts;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Resources\Cities\CityBriefResource;
use Modules\Core\Http\Resources\Countries\CountryBriefResource;
use Modules\Crm\Http\Resources\Clients\ClientBriefResource;
use Modules\Crm\Http\Resources\Leads\LeadBriefResource;

class ContactResource extends JsonResource
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
        $type = null;
        $relation = null;
        if($this->client_id){
            $type = 'client';
            $relation = new ClientBriefResource($this->client);

        }elseif($this->lead_id){
            $type = 'lead';
            $relation = new LeadBriefResource($this->lead);
        }
        return [
            'id'               => $this->id,
            'code'             => $this->code,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'phone'            => $this->phone,
            'mobile'           => $this->mobile,
            'email'            => $this->email,
            'address'          => $this->address,
            'position'         => $this->position,
            'city'             => new CityBriefResource($this->city),
            'country'          => new CountryBriefResource($this->country),
            'photo'            => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'type'             => $type,
            $type              => $relation,

        ];
    }
}
