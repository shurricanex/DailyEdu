<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function(){

Route::get('/user', function (Request $request) {
    return $request->user()->load('notifications');
});
Route::post('/logout','AuthController@logout');
Route::post('post/{post}/comments','CommentController@store');
Route::post('post/{post}/like','likeController@toggleLike');
Route::get('post/{post}/comments','CommentController@index');
Route::get('/posts','EntityController@index');
Route::get('/user/posts','EntityController@find');
Route::get('/group/posts','EntityController@find');
Route::post('/post','EntityController@store');
Route::post('share/{post}','ShareController@store');
Route::delete('/post/delete/{post}','EntityController@destroy');
Route::post('/post/delete/photo','EntityController@destroyPhoto');
Route::post('/post/edit','EntityController@update');
Route::patch('post/comment/{comment}','CommentController@update');
Route::delete('post/comment/{comment}','CommentController@destroy');
Route::get('/contacts', 'ContactsController@get');
Route::get('/conversation/{id}', 'ContactsController@getMessagesFor');
Route::post('/conversation/send', 'ContactsController@send');
Route::get('findUser', 'Admin\UserController@search');
Route::get('search', 'Admin\UserController@searchGlobal');
Route::get('members/', 'GroupController@getMembers');
Route::apiResource('/users', 'Admin\UserController');
Route::get('admin/log/{id}', 'Admin\LogController@show');
Route::get('admin/log/deleted/{id}', 'Admin\LogController@showDeleted');
Route::get('user/following','Admin\UserController@getFollowingUsers');
Route::post('follow/{id}','Admin\UserController@followOrNot');
Route::get('mark-as-read/{user}',function(User $user){
$user->unreadNotifications()->update(['read_at'=>now()]);
return $user->load('notifications');

});
Route::apiResource('groups','GroupController');
Route::post('import','ImportExportController@import');

});
Route::post('/login','AuthController@login');
