<?php

namespace Modules\Crm\Http\Resources\Leads;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;
use Modules\Core\Http\Resources\Classes\ClassBriefResource;
use Modules\Core\Http\Resources\Departments\DepartmentBriefResource;
use Modules\Crm\Http\Resources\LeadStages\LeadStageResource;
use Modules\Crm\Http\Resources\Sources\SourceResource;

class LeadResource extends JsonResource
{
    /**
     * Transform Lead Stage resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"                  => $this->id,
            'code'                => $this->code,
            'branch'              => new BranchBriefResource($this->branch),
            'stage'               => new LeadStageResource($this->stage),
            'source'              => new SourceResource($this->source),
            'is_client'           => $this->is_client,
            'type'                => $this->type,
            'company_name'        => $this->company_name,
            'contact_person'      => $this->contact_person,
            'first_name'          => $this->first_name,
            'last_name'           => $this->last_name,
            'phone'               => $this->phone,
            'mobile'              => $this->mobile,
            'email'               => $this->email,
            'address'             => $this->address,
            'location'            => $this->location,
            'city'                => $this->city,
            'country'             => $this->country,
            'website'             => $this->website,
            'facebook'            => $this->facebook,
            'linkedin'            => $this->linkedin,
            'industry'            => $this->industry,
            'longitude'           => $this->longitude,
            'latitude'            => $this->latitude,
            'number_of_employees' => $this->number_of_employees,
            'team'                => new DepartmentBriefResource($this->team),
            'class'               => new ClassBriefResource($this->class),
            'department'          => new DepartmentBriefResource($this->department),
            'assign_to'           => new BriefUserResource($this->assign),
            'details'             => $this->details,
            'photo'               => isset($this->photo) ? asset(Storage::url($this->photo->file)) : null,
            'documents'           => LeadDocumentsResource::collection($this->documents),
        ];
    }
}
