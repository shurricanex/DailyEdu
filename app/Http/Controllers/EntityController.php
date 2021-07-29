<?php

namespace App\Http\Controllers;
use App\Entity;
use App\Group;
use Illuminate\Http\Request;
use App\EntityCollection;
use App\Http\Resources\PostResource;
use Image;
use Illuminate\Support\Facades\Auth;
use App\User;
class EntityController extends Controller
{
    public $content;
    public function index(){
        $post = new Entity;
        $followingUser = Auth::user()->following()->pluck('following_id')->toArray();
        $AuthUserId = Auth::user()->id;
        array_push($followingUser,$AuthUserId);
        $posts =$post->whereIn('user_id',$followingUser)->whereNull('group_id')->with('user')->offset(request('offset'))->take(2)->latest()->get();


        return PostResource::collection($posts);
    }

    public function store(Request $request, Entity $post)
    {
        $this->validate($request,array(
            'caption' =>'sometimes|required|String',
            'group_id' => 'sometimes|required|String',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240'
        ));

             $user_id = Auth::user()->id;
             $group_id = $request->group_id;
             $post=$post->create([
            'user_id'=>$user_id,'group_id'=>$group_id
              ]);
            $images = $request->image;


            foreach($images as $image)
            {

                $filename = $image->getClientOriginalName().'.'.$image->getClientOriginalExtension();
                $image->move(storage_path('app/public/images/post_images/'), $filename);
                $this->content = $filename;

                $post->photos()->create([
                    'caption' => $request->caption,
                    'photo' => $this->content,
        ]);
      }
        return response()->json('Post had been uploaded',200);
    }

    public function destroy(Entity $post)
    {
        if($post->user_id !== Auth::user()->id)
        {
            return response()->json("Unauthenticated",401);
        }
        $post->delete();
        $post->photos()->delete();
        return response()->json("post ".$post->id." has been deleted");
    }
    public function destroyPhoto(Request $request)
    {
        if($request->user_id !== Auth::user()->id)
        {
            return response()->json("Unauthenticated",401);
        }
        $post = Entity::find($request->post_id);
        $post->photo()->whereIn('id',$request->photo_id)->delete();
        return response()->json("photos has been deleted");
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'caption' =>'sometimes|required|String',
            'group_id' => 'sometimes|required|String',
            'photo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:10240'
        ]);

        $input = $request->all();
        //update the passing the photo_id to null
        $post = Entity::find($request->entity_id);


        if($request->has('photo_id')){
            $post->photos()->whereIn('id',$request->photo_id)->update(['photo'=>null]);
        }
        $post->photos()->update(['caption'=>$request->caption]);
        return response()->json('post updated successfully',200);
    }

    public function find(Request $request)
    {
        if($request->userId){
        $user = User::find($request->userId);
        $posts =$user->posts()->with(['user','likes'])->offset(request('offset'))->take(2)->latest()->get();
    }
    if($request->groupId){
        $group = Group::find($request->groupId);
        $posts =$group->posts()->with(['user','likes'])->offset(request('offset'))->take(2)->latest()->get();
    }
        return PostResource::collection($posts);
    }

}
