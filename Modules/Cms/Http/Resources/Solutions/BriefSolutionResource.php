<?php

namespace Modules\Cms\Http\Resources\Solutions;

use Illuminate\Http\Resources\Json\JsonResource;

class BriefSolutionResource extends JsonResource
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
            'title' => $this->title_en,
        ];
    }
}
