<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entity;
use Auth;
class ShareController extends Controller
{
    public function store(Entity $post)
    {

        Entity::create([
            'parent_id'=>$post->id,
            'user_id'=>Auth::user()->id,
        ]);
        return response()->json("Sucessfully shared",200);
    }
    
}
