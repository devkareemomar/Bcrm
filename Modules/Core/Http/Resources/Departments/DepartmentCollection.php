<?php

namespace Modules\Core\Http\Resources\Departments;

use App\Http\Resources\PaginatedCollection;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;

class DepartmentCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'branch' => new BranchBriefResource($item->branch)
        ];
    }
}
