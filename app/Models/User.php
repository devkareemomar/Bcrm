<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Modules\Core\Traits\HasBranches;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Core\Models\Media;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable, LogsActivity, LaratrustUserTrait, HasBranches;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['branches'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // activity log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->useLogName('User')
            ->dontSubmitEmptyLogs();
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_id');
    }
}
