<?php

namespace Modules\Core\Http\Resources\Companies;

use App\Http\Resources\PaginatedCollection;

class CompanyCollection extends PaginatedCollection
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
            'website' => $item->website,
        ];
    }
}
