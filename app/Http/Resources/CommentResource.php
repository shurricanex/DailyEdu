<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'entity_id'=>$this->entity_id,
            'username'=>$this->user->name,
            'avatar_image'=>$this->user->avatar_image,
            'parent_id'=>$this->parent_id,
            'comment'=>$this->comment,
            'replies'=>self::collection($this->whenLoaded('replies')),
            'created_at'=>$this->created_at
        ];
    }
}
