<?php

namespace Modules\Crm\Http\Resources\Opportunities;

use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Users\BriefUserResource;
use Modules\Core\Http\Resources\Teams\TeamBriefResource;
use Modules\Crm\Http\Resources\Leads\LeadBriefResource;
use Modules\Crm\Http\Resources\Sources\SourceResource;

class OpportunityCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id'                =>   $item->id,
            'code'              =>   $item->code,
            'lead'              =>   new LeadBriefResource($item->lead),
            'subject'           =>   $item->subject,
            'date'              =>   $item->date,
            'team'              =>   new TeamBriefResource($item->team),
            'assign_to'         =>   new BriefUserResource($item->assign_to),
            'source'            => new SourceResource($item->source),
            'stage'             =>   $item->stage,
            'probability'       =>   $item->probability,
            'total_cost'        =>   $item->total_cost,
        ];
    }
}
