<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class group extends Model
{

    protected $with ='users';
    protected $guarded =[];
    public function users()
    {
        return $this->belongsToMany(User::class,'group_users','group_id','user_id')->using('App\group_user')->withPivot(['id','user_id','group_id']);
    }
    public function posts(){
        return $this->hasMany(Entity::class);
    }
}
