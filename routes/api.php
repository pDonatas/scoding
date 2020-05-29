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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
   Route::post('login', 'LoginController');
   Route::post('relog', 'LoginController@relog');
   Route::post('logout', 'LogoutController');
   route::post('me', 'MeController');
   Route::post('register', 'RegisterController@register');
});

//TasksCollection
Route::get('task/{id}', 'TaskController@show');
Route::delete('task/{id}', 'TaskController@destroy');
Route::post('tasks', 'TaskController@index');
Route::post('task', 'TaskController@store');
Route::put('task', 'TaskController@store');
//UsersCollection
Route::get('user/{id}', 'UserController@show');
Route::delete('user/{id}', 'UserController@destroy');
Route::post('users', 'UserController@index');
Route::post('allusers', 'UserController@all');
Route::post('user', 'UserController@store');
Route::put('user', 'UserController@store');
