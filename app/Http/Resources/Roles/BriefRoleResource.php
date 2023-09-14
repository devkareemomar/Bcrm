<?php

namespace App\Http\Resources\Roles;

use Illuminate\Http\Resources\Json\JsonResource;

class BriefRoleResource extends JsonResource
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
            'display_name' => $this->display_name
        ];
    }
}
