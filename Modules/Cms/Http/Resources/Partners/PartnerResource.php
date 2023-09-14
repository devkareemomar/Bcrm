<?php

namespace Modules\Cms\Http\Resources\Partners;

use App\Http\Resources\Users\BriefUserResource;
use Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cms\Http\Resources\Categories\BriefCategoryResource;

class PartnerResource extends JsonResource
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
            'title' => $this->title,
            'opinion' => $this->opinion,
            'evaluation' => $this->evaluation,
            'category' => new BriefCategoryResource($this->category),
            'logo' => isset($this->logo) ? asset(Storage::url($this->logo->file)) : null,
            'created_by' => new BriefUserResource($this->created_by),
            'updated_by' => new BriefUserResource($this->updated_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
