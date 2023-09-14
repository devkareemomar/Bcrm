<?php

namespace Modules\Cms\Http\Resources\Media;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    /**
     * Transform user resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'description' => $this->description,
            'title' => $this->title,
            'alt' => $this->alt,
            'url' => isset($this->file) ? asset(Storage::url($this->file)) : null,
            'created_by' => new BriefUserResource($this->created_by),
            'updated_by' => new BriefUserResource($this->updated_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
