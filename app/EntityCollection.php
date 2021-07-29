<?php
namespace App;
use App\Entity;
use Illuminate\Database\Eloquent\Collection;


Class EntityCollection extends Collection
{
    public function loadRelations()
    {
        $ids = array_map(function(Entity $entity){return $entity->getParentId();},$this->items);
        if($ids){
            $this->load('parent');
        }
    }
}

