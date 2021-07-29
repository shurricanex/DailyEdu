<?php

namespace App;

use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\PostLiked;

class Entity_like extends Model
{

    protected $fillable =['user_id','entity_id'];

  

    public function user(){

        return $this->belongsTo(User::class);
    }
    public function post(){
        return $this->belongsTo(Entity::class,'entity_id');
    }
}
