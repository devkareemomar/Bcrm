<?php

namespace Modules\Inventory\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Media;

class Transfer extends Model
{
    protected $table = 'sto_transfers';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromStore()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function toStore()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'sto_transfer_items')->withPivot(['id', 'quantity','unit_id'])->using(TransferItem::class);
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

}
