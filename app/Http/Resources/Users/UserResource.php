<?php

namespace App\Http\Resources\Users;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Roles\BriefRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
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
            'email' => $this->email,
            'photo' => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'roles' => BriefRoleResource::collection($this->roles),
        ];
    }
}
