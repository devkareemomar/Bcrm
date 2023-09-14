<?php

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    public $timestamps = false;
    
    protected $table = 'core_team_user';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
