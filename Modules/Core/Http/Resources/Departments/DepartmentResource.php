<?php

namespace Modules\Core\Http\Resources\Departments;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;

class DepartmentResource extends JsonResource
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
            'branch' => new BranchBriefResource($this->branch)
        ];
    }
}
