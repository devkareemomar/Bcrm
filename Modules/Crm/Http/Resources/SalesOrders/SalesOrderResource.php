<?php

namespace Modules\Crm\Http\Resources\SalesOrders;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;
use Modules\Core\Http\Resources\Companies\CompanyResource;
use Modules\Core\Http\Resources\Currencies\CurrencyBriefResource;
use Modules\Core\Http\Resources\Departments\DepartmentBriefResource;
use Modules\Core\Http\Resources\Teams\TeamBriefResource;
use Modules\Crm\Http\Resources\Clients\ClientBriefResource;
use Modules\Crm\Http\Resources\Contacts\ContactBriefResource;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Http\Resources\Opportunities\OpportunityBriefResource;
use Modules\Crm\Http\Resources\PaymentTerms\PaymentTermResource;
use Modules\Crm\Http\Resources\Quotations\QuotationBriefResource;

class SalesOrderResource extends JsonResource
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
            'id'                => $this->id,
            'code'              => $this->code,
            'subject'           => $this->subject,
            'date'              => $this->date,
            'delivery_date'     => $this->delivery_date,
            'delivery_address'  => $this->delivery_address,
            'stage'             => $this->stage,
            'terms'             => $this->terms,
            'descriptions'      => $this->descriptions,
            'approved_by'       => new BriefUserResource($this->whenLoaded('approved_by')),
            'document'          => isset($this->document) ? asset(Storage::url($this->document->file)) : null,
            'currency'          => new CurrencyBriefResource($this->whenLoaded('currency')),
            'company'           =>   new CompanyResource($this->branch->company),
            'branch'            =>   new BranchBriefResource($this->whenLoaded('branch')),
            'client'            =>  new ClientBriefResource($this->whenLoaded('client')),
            'contact'           =>  new ContactBriefResource($this->whenLoaded('contact')),
            'department'        => new DepartmentBriefResource($this->whenLoaded('department')),
            'team'              => new TeamBriefResource($this->whenLoaded('team')),
            'assign_to'         => new BriefUserResource($this->whenLoaded('assign_to')),
            'quotation'         => new QuotationBriefResource($this->whenLoaded('quotation')),
            'opportunity'       => new OpportunityBriefResource($this->whenLoaded('opportunity')),

            'payment_type'      =>  $this->payment_type,
            'sub_total'         =>  $this->sub_total,
            'total_tax'         =>  $this->total_tax,
            'total_discount'    =>  $this->total_discount,
            'total_cost'        =>  $this->total_cost,

            'paymentTerms'      =>   PaymentTermResource::collection($this->whenLoaded('paymentTerms')),
            'salesOrderDetails' => ItemDetailsResource::collection($this->whenLoaded('salesOrderDetails')),


        ];
    }
}
