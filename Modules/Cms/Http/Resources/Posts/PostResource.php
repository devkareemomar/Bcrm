<?php

namespace Modules\Cms\Http\Resources\Posts;

use App\Http\Resources\Users\BriefUserResource;
use Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cms\Http\Resources\Categories\BriefCategoryResource;

class PostResource extends JsonResource
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
            'id'                  => $this->id,
            'slug_ar'             => $this->slug_ar,
            'slug_en'             => $this->slug_en,
            'title_ar'            => $this->title_ar,
            'title_en'            => $this->title_en,
            'description_ar'      => $this->description_ar,
            'description_en'      => $this->description_en,
            'category'            => new BriefCategoryResource($this->category),
            'type'                => $this->type,
            'photo'               => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'author'              => $this->author,
            'is_active'           => $this->is_active,
            "comments_count"      => $this->comments_count,
            'meta_title_ar'       => $this->meta_title_ar,
            'meta_title_en'       => $this->meta_title_en,
            'meta_keyword_ar'     => $this->meta_keyword_ar,
            'meta_keyword_en'     => $this->meta_keyword_en,
            'meta_description_ar' => $this->meta_description_ar,
            'meta_description_en' => $this->meta_description_en,
            'custom_code_head'    => $this->custom_code_head,
            'og_type'             => $this->og_type,
            'created_by'          => new BriefUserResource($this->created_by),
            'updated_by'          => new BriefUserResource($this->updated_by),
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }
}
