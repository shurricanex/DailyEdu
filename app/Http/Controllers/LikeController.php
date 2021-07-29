<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entity;
use App\Entity_like;
use App\Notifications\PostLiked;
use Illuminate\Support\Facades\Notification;
class LikeController extends Controller
{


    public function toggleLike(Request $request, Entity $post)
    {

        $userId = Auth::user()->id;
        $entityId = $request->entity_id;
        $exist = Entity_like::where([['entity_id',$entityId],['user_id',$userId]]);

        if($exist->first()!==null){
           $exist->delete();
           return response()->json("exist",200);
        }
        else{

            $post->likes()->create([
                'user_id'=>$userId,
                'entity_id'=>$entityId
            ]);
            //bug : it will be repeat on the same user which already has been notified to target notifiable user
            //should take the current user,
            $likes = $post->likes()->latest()->first();
            // $userLike = $likes->map(function($like){
            //     return $like->user;
            // });
            // try debugging
            // $userLike = Auth::user();
            // result in duplicate entry of all auth users
                $userLike = $likes->user;
            $user = $post->user;
            Notification::send($user,new PostLiked($userLike));

            return response()->json('nonexist',200);
        }


    }


}
