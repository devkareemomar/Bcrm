<?php

namespace Modules\Crm\Http\Resources\Leads;

use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Users\BriefUserResource;
use Modules\Crm\Http\Resources\LeadStages\LeadStageResource;
use Modules\Crm\Http\Resources\Sources\SourceResource;

class LeadCollection extends PaginatedCollection
{

    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            "id"             => $item->id,
            'code'           => $item->code,
            'name'           => $item->first_name . ' '.$item->last_name,
            'phone'          => $item->phone,
            'email'          => $item->email,
            'type'           => $item->type,
            'source'         => new SourceResource($item->source),
            'stage'          => new LeadStageResource($item->stage),
            'assign_to'      => new BriefUserResource($item->assign),
        ];
    }
}
