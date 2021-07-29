<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Photo;
use App\Entity;
class PostResource extends JsonResource
{

    // public $collects = 'App\Http\Resources\Entity';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {

        if($this->parent_id===null){
        foreach($this->photos as $photo)
        {
            $photos[]=array(
                        'id'=>$photo->id,
                        'caption'=>$photo->caption,
                        'photo'=>$photo->photo,
                      );

        }
        }
         else{
             $photos = null;
         }

        if(count($this->likes)==0){
            $likes='empty';
         }
         $likes=array();
         foreach($this->likes as $like){
            array_push($likes,$like->user);
    }
        return [
            'entity_id'=>$this->id,
            'photos'=>$photos,
            'user_id'=>$this->user->id,
            'username' =>$this->user->name,
            'avatar_image' => $this->user->avatar_image,
            'like' => $this->likes->count(),
            'share' =>(Entity::where('parent_id',$this->id)->count()),
            'user_liked' =>$likes,
            'parent' =>new PostResource($this->whenLoaded('parent')),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }


}
