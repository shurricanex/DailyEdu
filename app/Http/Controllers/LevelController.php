<?php

namespace App\Http\Controllers;

use App\group;
use App\group_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GroupResource;
class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLevel(){
        $groupsOwned = Auth::user()->groups->pluck('id')->toArray();
       $level_id = Group::whereIn('id',$groupsOwned)->where('administrative',1)->value('id');
        return $level_id;
    }
    public function index()
    {
        $groups =  Group::where('administrative',1)->withCount('users')->orderBy('users_count','desc')->take(10)->get();
        return GroupResource::collection($groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $group = Group::create(['name'=>$request->name,'administrative'=>1]);
        $users = collect($request->users);
        $authUser = Auth::user()->id;
        $users->push($authUser);
         $group->users()->attach($users);
        $group_user = $group->users()->where('user_id',$authUser)->first();
        $group_user->pivot->assignRole('admin');
        return response()->json('Successfully created the group');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\group  $group
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $group = Group::where('id',$id)->get();
         return GroupResource::collection($group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(group $group)
    {
        //
    }
    public function JoinOrNot($id)
    {
        $jointUser = Auth::user()->groups();
        if($jointUser->contains($id))
        {
        $jointUser->detach($id);
        return response()->json('Unfollowed',200);

        }
        else{
        $jointUser->attach($id);
        return response()->json('Followed',200);
        }
    }


    public function getMembers(Request $request)
    {
        $group = Group::find($request->group_id);
        $users = $group->users()->get();
        $users = $users->map(function($user){
            return ['name'=>$user->name,'avatar_image'=>$user->avatar_image,'role'=>$user->pivot->getRoleNames()];
        });
        return $users;
    }
}
