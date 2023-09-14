<?php

namespace Modules\Crm\Http\Resources\Activities;

use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Users\BriefUserResource;

class ActivityCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        $pieces = explode('\\', $item->activitable_type);
        $module = array_pop($pieces);

        return [
            'id'                  => $item->id,
            'code'                => $item->code,
            'date'                => $item->date,
            'reminder_date'       => $item->reminder_date,
            'type'                => $item->type,
            'description'         => $item->description,
            'activitable_type'    => $module,
            'activitable_code'    => $item->activitable->code ?? '',
            'assignTo'            => new BriefUserResource($item->assignTo),
        ];
    }
}
