<?php

namespace App\Http\Resources\Roles;

use App\Http\Resources\PaginatedCollection;

class RoleCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            "id" => $item->id,
            'name' => $item->name,
            'display_name' => $item->display_name,
            'permissions_count' => $item->permissions_count,
            "users_count" => $item->users_count
        ];
    }
}
