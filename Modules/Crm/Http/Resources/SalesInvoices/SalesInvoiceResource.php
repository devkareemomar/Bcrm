<?php

namespace Modules\Crm\Http\Resources\SalesInvoices;

use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Resources\Currencies\CurrencyBriefResource;
use Modules\Core\Http\Resources\Departments\DepartmentBriefResource;
use Modules\Core\Http\Resources\Teams\TeamBriefResource;
use Modules\Crm\Http\Resources\Clients\ClientBriefResource;
use Modules\Crm\Http\Resources\Contacts\ContactBriefResource;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Http\Resources\Opportunities\OpportunityBriefResource;
use Modules\Crm\Http\Resources\Quotations\QuotationBriefResource;
use Modules\Crm\Http\Resources\SalesOrders\SalesOrderBriefResource;

class SalesInvoiceResource extends JsonResource
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
            'due_date_payment'  => $this->due_date_payment,
            'delivery_date'     => $this->delivery_date,
            'delivery_address'  => $this->delivery_address,
            'stage'             => $this->stage,
            'terms'             => $this->terms,
            'notes'             => $this->notes,
            'approved_by'       => new BriefUserResource($this->whenLoaded('approved_by')),
            'document'          => isset($this->document) ? asset(Storage::url($this->document->file)) : null,
            'currency'          => new CurrencyBriefResource($this->whenLoaded('currency')),
            'department'        => new DepartmentBriefResource($this->whenLoaded('department')),
            'client'            => new ClientBriefResource($this->whenLoaded('client')),
            'contact'           => new ContactBriefResource($this->whenLoaded('contact')),
            'team'              => new TeamBriefResource($this->whenLoaded('team')),
            'assign_to'         => new BriefUserResource($this->whenLoaded('assign_to')),
            'quotation'         => new QuotationBriefResource($this->whenLoaded('quotation')),
            'opportunity'       => new OpportunityBriefResource($this->whenLoaded('opportunity')),
            'salesOrder'        => new SalesOrderBriefResource($this->whenLoaded('salesOrder')),

            'payment_type'      =>  $this->payment_type,
            'sub_total'         =>  $this->sub_total,
            'total_tax'         =>  $this->total_tax,
            'total_discount'    =>  $this->total_discount,
            'total_cost'        =>  $this->total_cost,

            'salesInvoiceDetails' => ItemDetailsResource::collection($this->whenLoaded('salesInvoiceDetails')),
        ];
    }
}
