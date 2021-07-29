<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Entity;
use Illuminate\Support\Facades\File;

use App\Group;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $users =  User::latest()->paginate(10);
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:users',
            'email' => '|string|email|max:191|unique:users',
            // 'password' => 'required|string|min:6'
        ]);


        return User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'type'=>'user',
        'password' => Hash::make('dailyedu'),
        ]);
    }

    public function search(Request $request){

        if($search = $request['q']){
            $users = User::where(function($query) use($search){
            $query->where('name','LIKE',"%$search%");
            })->paginate(10);
        }
        else{
            $users = User::latest()->paginate(10);
        }

        return  $users;

    }
    public function searchGlobal(Request $request){

        if($search = $request['q']){
            $users = User::where(function($query) use($search){
            $query->where('name','LIKE',"%$search%");
            })->get();
            $groups = Group::where(function($query) use($search){
            $query->where('name',$search);
            })->get();
            $photos = Entity::whereHas('photos',function(Builder $query) use($search){
                $query->where('caption',$search);
            })->get();
           ;


            // dd($posts);
         $res1 = array_merge(['users'=>$users->toArray()],['photos'=>$photos->toArray()]);
         $res2 = array_merge($res1,['groups'=>$groups->toArray()]);
        }
        else{
            $res = User::latest()->paginate(10);
        }

        return  $res2;

    }

    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users,email,'.$id,
        //     // 'password' => 'same:confirmpassword',
        //     'level' => 'required'
        // ]);


        $input = $request->all();
        if ($request->hasFile('cover_image')) {

            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $inputImage = $filename . '_' . time() . '_.' . $extension;
            // $request->file('cover_image')->storeAs('app/public/images/cover_images/', $inputImage);
            $request->file('cover_image')->move(storage_path('app/public/images/cover_images/'), $inputImage);

            // delete image from folder in both public and storage
            // $image = User::find($id)->value('cover_image');
            // $path = '/images/'.$image;
            // $fullPath = public_path($path);
            // if (File::exists($fullPath)) {
            //     unlink($fullPath);
            // }
            // $path = '/app/public/images/cover_images/'.$image;
            // $fullPath = storage_path($path);
            // if (File::exists($fullPath)) {
            //     unlink($fullPath);
            // }
            //change from file to file name instead
            $input['cover_image'] = $inputImage;
        }
        if ($request->hasFile('avatar_image')) {

            $filenameWithExt = $request->file('avatar_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('avatar_image')->getClientOriginalExtension();
            $inputImage = $filename . '_' . time() . '_.' . $extension;
            // $request->file('cover_image')->storeAs('app/public/images/cover_images/', $inputImage);
            $request->file('avatar_image')->move(storage_path('app/public/images/avatar_images/'), $inputImage);

            // delete image from folder in both public and storage
            // $image = User::find($id)->value('cover_image');
            // $path = '/images/'.$image;
            // $fullPath = public_path($path);
            // if (File::exists($fullPath)) {
            //     unlink($fullPath);
            // }
            // $path = '/app/public/images/cover_images/'.$image;
            // $fullPath = storage_path($path);
            // if (File::exists($fullPath)) {
            //     unlink($fullPath);
            // }
            //change from file to file name instead
            $input['avatar_image'] = $inputImage;
        }
        $user = User::find($id);
        $user->update($input);


        return response()->json('User updated successfully',200);
    }
    public function show($id)
    {
         $user = User::where('id',$id)->get();
         return UserResource::collection($user);
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        return response()->json('User deleted successfully',200);
    }

    public function followOrNot($id)
    {
        $followingUser = Auth::user()->following();
        $followingUserArr = $followingUser->get()->pluck('id');
        if($followingUserArr->contains($id))
        {
        $followingUser->detach($id);
        return response()->json('Unfollowed',200);

        }
        else{
        $followingUser->attach($id);
        return response()->json('Followed',200);
        }
    }

    public function getFollowingUsers(Request $request)
    {
        $user = User::find($request->user_id);

        if($request->followingOrFollower==='true'){
        $users = $user->following()->get();
        }
        if($request->followingOrFollower==='false'){
            $users = $user->follower()->get();

        }

        $users = $users->map(function($user){
            return ['name'=>$user->name, 'avatar_image'=>$user->avatar_image];
        });
         return $users;
    }



}
