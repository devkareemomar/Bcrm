<?php

namespace Modules\Crm\Http\Resources\Clients;

use App\Http\Resources\PaginatedCollection;

class ClientCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id'                => $item->id,
            'code'              => $item->code,
            'first_name'        => $item->first_name,
            'last_name'         => $item->last_name,
            'phone'             => $item->phone,
            'email'             => $item->email,
            'contact_person'    => $item->contact_person,
            'type'              => $item->type,

        ];
    }
}
