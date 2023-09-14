<?php

namespace Modules\Inventory\Models;

use App\Models\User;
use Modules\Core\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'sto_stores';

    protected $guarded = [];

    public function storekeeper()
    {
        return $this->belongsTo(User::class, 'storekeeper_user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
