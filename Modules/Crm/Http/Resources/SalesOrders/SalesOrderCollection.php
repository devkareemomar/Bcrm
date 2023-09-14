<?php

namespace Modules\Crm\Http\Resources\SalesOrders;

use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Users\BriefUserResource;
use Modules\Core\Http\Resources\Teams\TeamBriefResource;
use Modules\Crm\Http\Resources\Clients\ClientBriefResource;
use Modules\Crm\Http\Resources\Contacts\ContactBriefResource;

class SalesOrderCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id'          =>  $item->id,
            'code'        =>  $item->code,
            'client'      =>  isset($item->client->first_name) ? $item->client->first_name .' '. $item->client->last_name : '',
            'subject'     =>  $item->subject,
            'date'        =>  $item->date,
            'contact'     =>  isset($item->contact->first_name) ? $item->contact->first_name .' '. $item->contact->last_name : '',
            'team'        =>  new TeamBriefResource($item->team),
            'assign_to'   =>  new BriefUserResource($item->assign_to),
            'stage'       =>  $item->stage,
            'total_cost'  =>  $item->total_cost,
        ];
    }
}
