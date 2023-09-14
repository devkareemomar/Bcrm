<?php

namespace Modules\Core\Http\Resources\Media;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'url' => isset($this->file) ? asset(Storage::url($this->file)) : null,
            'created_by' => new BriefUserResource($this->created_by),
            'updated_by' => new BriefUserResource($this->updated_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
