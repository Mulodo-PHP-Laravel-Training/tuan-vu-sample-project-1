<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/', function ()
    {
        return view('welcome');
    });


});
Route::resource('user', 'UserController', ['except' => ['create', 'edit']]);
Route::post('/auth/signin', 'TokenAuth\TokenAuthController@authenticate');
Route::get('/auth/user', 'TokenAuth\TokenAuthController@getAuthenticatedUser');