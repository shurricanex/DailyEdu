<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Entity_comment extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $fillable = ['parent_id','user_id','entity_id','comment'];
    protected static $logFillable = true;
    protected static $recordEvents = ['updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "has {$eventName} a comment.";
    }
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * define HasMany relationship
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Entity_comment::class, 'parent_id');
    }

    /**
     * Define relationship for App\Model
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Entity_comment::class);
    }

    public function user(){

        return $this->belongsTo(User::class);
    }
    public function post(){
        return $this->belongsTo(Entity::class,'entity_id');
    }
}
