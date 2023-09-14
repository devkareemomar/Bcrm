<?php

namespace Modules\Core\Http\Resources\Teams;


use Illuminate\Http\Resources\Json\JsonResource;


class UserTeamResource extends JsonResource
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
        return $this->team_id;
    }
}
