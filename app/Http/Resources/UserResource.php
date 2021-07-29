<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $original = parent::toArray($request);
        $following = $this->following()->get();
        $following = $following->count();
        $followers = $this->follower()->get();
        $isFollower  = $followers->contains(Auth::user()->id);
        $followers = $followers->count();
        $additional =
                    [
                       'follow'=>$isFollower,'following'=>$following,'followers'=>$followers

                    ];


        return array_merge($original,$additional);
    }
}
