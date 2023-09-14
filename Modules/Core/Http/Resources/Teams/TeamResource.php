<?php

namespace Modules\Core\Http\Resources\Teams;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Departments\DepartmentBriefResource;

class TeamResource extends JsonResource
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
            'description' => $this->description,
            'department' => new DepartmentBriefResource($this->department)
        ];
    }
}
