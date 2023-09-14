<?php

namespace Modules\Cms\Http\Resources\Sliders;

use App\Http\Resources\Users\BriefUserResource;
use Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cms\Http\Resources\Categories\BriefCategoryResource;

class SliderResource extends JsonResource
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
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'category' => new BriefCategoryResource($this->category),
            'photo' => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'created_by' => new BriefUserResource($this->created_by),
            'updated_by' => new BriefUserResource($this->updated_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
