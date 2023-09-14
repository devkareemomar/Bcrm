<?php

namespace Modules\Crm\Http\Resources\Contacts;

use App\Http\Resources\PaginatedCollection;
use Illuminate\Support\Facades\Storage;
use Modules\Crm\Http\Resources\Clients\ClientBriefResource;
use Modules\Crm\Http\Resources\Leads\LeadBriefResource;

class ContactCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        $type = null;
        $relation = null;
        if($item->client_id){
            $type = 'client';
            $relation = new ClientBriefResource($item->client);

        }elseif($item->lead_id){
            $type = 'lead';
            $relation = new LeadBriefResource($item->lead);
        }


        return [
            'id'               => $item->id,
            'code'             => $item->code,
            'first_name'       => $item->first_name,
            'last_name'        => $item->last_name,
            'phone'            => $item->phone,
            'email'            => $item->email,
            'address'          => $item->address,
            'position'         => $item->position,
            'photo'            => isset($item->photo) ? asset(Storage::url($item->photo->file)) : null,
            $type              => $relation,
        ];
    }
}
