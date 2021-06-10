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
//login


route::post('register','school\AuthController@register');
route::post('login', 'school\AuthController@login');
route::post('forgotPassword', 'school\AuthController@forgotPassword');
route::get('logout', 'school\AuthController@logout');
route::post('reset', 'school\AuthController@reset');
route::get('login_list', 'school\AuthController@index');
route::post('update', 'school\AuthController@update');
route::get('destroy', 'school\AuthController@destroy');

route::get('settings', 'school\CommonController@settings');
route::get('userStatus', 'school\CommonController@userStatus');


//online registration
route::post('onlineRegistration_store','school\OnlineRegistrationController@store');
route::get('onlineRegistration_show','school\OnlineRegistrationController@show');
route::post('onlineRegistration_update','school\OnlineRegistrationController@update');
route::get('onlineRegistration_destroy','school\OnlineRegistrationController@destroy');
route::get('onlineRegistration_list','school\OnlineRegistrationController@index');

