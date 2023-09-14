<?php

namespace Modules\Core\Http\Resources\ActivityLogs;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;


class ActivityLogResource extends JsonResource
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
            'status' => $this->event,
            'description' => $this->description,
            'changes' => $this->changes,
            'caused_by' => new BriefUserResource($this->causer),
            'created_at' => $this->created_at,
        ];
    }
}
