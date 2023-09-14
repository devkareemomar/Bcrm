<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\PaginatedCollection;
use Illuminate\Support\Facades\Storage;

class UserCollection extends PaginatedCollection
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
            'email' => $item->email,
            'photo' => isset($item->photo) ? asset(Storage::url($item->photo->file)) : null,
            'roles_count' => $item->roles_count,
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }
}
