<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/** ========================With Auth Routes==================== */ 
Route::post('login', 'JWTAuthController@login');
Route::post('signup', 'JWTAuthController@register');
Route::post('confirm', 'JWTAuthController@confirm');


/*========================ADMIN Route=========================== */
Route::group(['middleware' => 'admin','prefix' =>'admin' ], function(){
    Route::post('invite-user', 'UserController@inviteUser');
 });

/*========================User Route=========================== */
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('logout', 'JWTAuthController@logout');
    Route::get('profile', 'JWTAuthController@profile');
    Route::post('update-profile', 'UserController@updateProfile');
});

