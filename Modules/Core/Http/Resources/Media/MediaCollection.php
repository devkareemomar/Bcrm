<?php

namespace Modules\Core\Http\Resources\Media;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PaginatedCollection;

class MediaCollection extends PaginatedCollection
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
            'mime_type' => $item->mime_type,
            'extension' => $item->extension,
            'url' => isset($item->file) ? asset(Storage::url($item->file)) : null
        ];
    }
}
