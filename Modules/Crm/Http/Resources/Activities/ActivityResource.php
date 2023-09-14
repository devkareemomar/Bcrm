<?php

namespace Modules\Crm\Http\Resources\Activities;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ActivityResource extends JsonResource
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

        $pieces = explode('\\', $this->activitable_type);
        $module = array_pop($pieces);
        return [
            'id'                  => $this->id,
            'code'                => $this->code,
            'date'                => $this->date,
            'reminder_date'       => $this->reminder_date,
            'type'                => $this->type,
            'description'         => $this->description,
            'activitable_type'    => $module,
            'activitable_code'    => $this->activitable->code ?? '',
            'document'            => isset($this->document) ? asset(Storage::url($this->document->file)) : null,
            'createdBy'           => new BriefUserResource($this->whenLoaded('createdBy')),
            'assignTo'            => new BriefUserResource($this->whenLoaded('assignTo')),
        ];
    }
}
