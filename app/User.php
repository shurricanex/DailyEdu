<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    protected $guard_name = 'api';
    use HasApiTokens, Notifiable,CausesActivity,logsActivity,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded =[];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function following()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','following_id');
    }

    public function follower()
    {
        return $this->belongsToMany(User::class,'followers','following_id','follower_id');
    }
    public function posts()
    {
        return $this->hasMany(Entity::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class,'group_users','user_id','group_id');
    }

}
