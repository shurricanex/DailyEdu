<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Entity_comment;
use App\Http\Resources\LogResource;
class LogController extends Controller
{
    function show(Request $request){

        $activities = User::find($request->id)->actions;
        return $activities;
    }
    function showDeleted(Request $request){


            $selectedComment = Entity_comment::with('user','post')->withTrashed()->where('id','=',$request->id)->get();
            return LogResource::collection($selectedComment);



    }
}
