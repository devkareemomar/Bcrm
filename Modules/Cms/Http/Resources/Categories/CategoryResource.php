<?php

namespace Modules\Cms\Http\Resources\Categories;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
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
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'photo' => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'type' => $this->type,
            'created_by' => new BriefUserResource($this->created_by),
            'updated_by' => new BriefUserResource($this->updated_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
