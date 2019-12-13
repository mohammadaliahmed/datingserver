<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(['prefix' => 'user'], function () {

    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::post('updateFcmKey', 'UserController@updateFcmKey');
    Route::post('phoneVerify', 'UserController@phoneVerify');
    Route::post('updateProfilePic', 'UserController@updateProfilePic');
    Route::post('uploadProfilePicture', 'UserController@uploadProfilePicture');
});


Route::Post('allUsers', 'UserController@allUsers');
Route::Get('sendMail', 'UserController@sendMail');

Route::group(['prefix' => 'room'], function () {

    Route::post('createRoom', 'RoomController@createRoom');
});
Route::group(['prefix' => 'message'], function () {

    Route::post('createMessage', 'MessageController@createMessage');
    Route::post('allRoomMessages', 'MessageController@allRoomMessages');
    Route::post('userMessages', 'MessageController@userMessages');
});