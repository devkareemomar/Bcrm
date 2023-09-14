<?php

namespace Modules\Crm\Http\Resources\SalesInvoices;

use App\Http\Resources\PaginatedCollection;

class SalesInvoiceCollection extends PaginatedCollection
{
    /**
     * method to change pagination data shape instead of default Resource
     * @param Collection  $item
     * @return array
     */
    public function _toArray($item)
    {
        return [
            'id'                => $item->id,
            'code'              => $item->code,
            'stage'             => $item->stage,
            'subject'           => $item->subject,
            'date'              => $item->date,
            'due_date_payment'  => $item->due_date_payment,
            'delivery_date'     => $item->delivery_date,
            'delivery_address'  => $item->delivery_address,
            'payment_type'      => $item->payment_type,
            'notes'             => $item->notes,
        ];

    }
}
