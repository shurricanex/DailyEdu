<?php

namespace App\Http\Controllers;
use App\Entity;
use App\Entity_comment;
use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\CommentResource;
 use App\Events\SendComment;
class CommentController extends Controller
{
    public function index(Entity $post){

        $comments = $post->comments()->parent()->with(['replies','user'])->get();
        return CommentResource::collection($comments);


    }

    public function store(Request $request, Entity $post){

      $comment = $post->comments()->create([

            'parent_id'=>$request->parent_id,
            'comment'=>$request->message,
            'user_id'=>Auth::user()->id
        ]);
        broadcast(new SendComment($comment));

        return response()->json($comment);

    }
    public function destroy(Entity_comment $comment)
    {
        if($comment->user_id !== Auth::user()->id)
        {
            return response()->json('Unauthorized',401);
        }
        $comment->delete();

        return response()->json('Comment with id '.$comment->id.' has been deleted');

    }

    public function update(Request $request, Entity_comment $comment)
    {
        if($comment->user_id !== Auth::user()->id)
        {
            return response()->json('Unauthorized',401);
        }

        $dataTobeUpdated = $request->validate([

            'comment' => 'required|string'
        ]);

        $comment->update($dataTobeUpdated);

        return response($comment, 200);

    }
}
