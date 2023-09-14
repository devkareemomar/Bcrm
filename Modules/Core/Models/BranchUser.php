<?php

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{   
    public $timestamps = false;
    
    protected $table = 'core_branch_user';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
