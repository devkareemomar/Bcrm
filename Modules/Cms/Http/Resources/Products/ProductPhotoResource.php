<?php

namespace Modules\Cms\Http\Resources\Products;

use App\Http\Resources\Users\BriefUserResource;
use Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cms\Http\Resources\Categories\BriefCategoryResource;

class ProductPhotoResource extends JsonResource
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
            'id'    => $this->id,
            'photo' => isset($this->file) ? asset(Storage::url($this->file)) : null,
        ];
    }
}
