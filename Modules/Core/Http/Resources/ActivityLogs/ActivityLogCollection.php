<?php

namespace Modules\Core\Http\Resources\ActivityLogs;

use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Users\BriefUserResource;

class ActivityLogCollection extends PaginatedCollection
{
    // /**
    //  * method to change pagination data shape instead of default Resource
    //  * @param Collection  $item
    //  * @return array
    //  */
    public function _toArray($item)
    {
        return [
            'status' => $item->event,
            'description' => $item->description,
            'changes' => $item->changes,
            'caused_by' => new BriefUserResource($item->causer),
            'created_at' => $item->created_at,

        ];
    }
}
