<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Entity_comment;
use App\Entity_like;
use App\Entity_share;
use App\EntityCollection;
use Spatie\Activitylog\Traits\LogsActivity;

class Entity extends Model
{
    use LogsActivity;
    protected $with = ['photos','parent','likes'];
    protected $fillable=['parent_id','user_id','group_id'];
    // protected $logAttributes = ['comments.id','photos.id','user.id','parent_id'];
    protected static $logFillable = true;
    protected static $recordEvents = ['deleted'];


    public function parent()
    {
        return $this->belongsTo(Entity::class,'parent_id');
    }

    public function comments() {

       return  $this->hasMany(Entity_comment::class);
    }

    public function likes() {

        return $this->hasMany(Entity_like::class);
    }

    public function shares() {

        return $this->hasMany(Entity::class,'parent_id');
    }

    public function photos(){

        return $this->hasMany(Photo::class,'entity_id');
    }

    public function user(){

        return $this->belongsTo('App\User','user_id');

    }

    public function group(){

        return $this->belongsTo('App\Group','group_id');

    }






// public function newCollection(array $models = []){
//     return new EntityCollection($models);
// }
}
