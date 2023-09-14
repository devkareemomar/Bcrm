<?php

namespace Modules\Crm\Http\Resources\Opportunities;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;
use Modules\Core\Http\Resources\Companies\CompanyResource;
use Modules\Core\Http\Resources\Currencies\CurrencyBriefResource;
use Modules\Core\Http\Resources\Departments\DepartmentBriefResource;
use Modules\Core\Http\Resources\Teams\TeamBriefResource;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Http\Resources\Leads\LeadBriefResource;
use Modules\Crm\Http\Resources\PaymentTerms\PaymentTermResource;
use Modules\Crm\Http\Resources\Sources\SourceResource;

class OpportunityResource extends JsonResource
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
            'id'                  =>   $this->id,
            'code'                =>   $this->code,
            'date'                =>   $this->date,
            'subject'             =>   $this->subject,
            'stage'               =>   $this->stage,
            'probability'         =>   $this->probability,
            'description'         =>   $this->description,
            'terms'               =>   $this->terms,
            'source'              =>   new SourceResource($this->source),
            'document'            =>   isset($this->document) ? asset(Storage::url($this->document->file)) : null,
            'currency'            =>   new CurrencyBriefResource($this->whenLoaded('currency')),
            'company'             =>   new CompanyResource($this->branch->company),
            'lead'                =>   new LeadBriefResource($this->whenLoaded('lead')),
            'branch'              =>   new BranchBriefResource($this->whenLoaded('branch')),
            'department'          =>   new DepartmentBriefResource($this->whenLoaded('department')),
            'team'                =>   new TeamBriefResource($this->whenLoaded('team')),
            'assign_to'           =>   new BriefUserResource($this->whenLoaded('assign_to')),
            'payment_type'        =>   $this->payment_type,
            'sub_total'           =>   $this->sub_total,
            'total_tax'           =>   $this->total_tax,
            'total_discount'      =>   $this->total_discount,
            'total_cost'          =>   $this->total_cost,


            'paymentTerms'        =>   PaymentTermResource::collection($this->whenLoaded('paymentTerms')),
            'opportunityDetails'  =>   ItemDetailsResource::collection($this->whenLoaded('opportunityDetails')),
        ];
    }
}
