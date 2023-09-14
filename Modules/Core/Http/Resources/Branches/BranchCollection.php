<?php

namespace Modules\Core\Http\Resources\Branches;

use App\Http\Resources\PaginatedCollection;
use Modules\Core\Http\Resources\Cities\CityBriefResource;
use Modules\Core\Http\Resources\Companies\CompanyBriefResource;

class BranchCollection extends PaginatedCollection
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
            'address' => $item->address,
            'phone' => $item->phone,
            'email' => $item->email,
            'company' => new CompanyBriefResource($item->company),
            'city' => isset($item->city) ? new CityBriefResource($item->city) : null,
        ];
    }
}
