<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $roles = $this->users()->get()->map(function ($item) {
             $roles = $item->pivot->getRoleNames();
            return (object)['role'=>$roles];
        });
        $zip = $this->users()->get()->zip($roles);
        $parent = parent::toArray($request);

        $add = [
            'member_numbers'=>$this->users()->get()->count()
        ];
        return array_merge($parent, $add);
    }
}
