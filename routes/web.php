<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Auth::routes();


Route::get('/', function () {
    echo "welcome! who are you!";
});




Route::get('/gplay/{id}/{key}/{latitude}/{longitude}', function ($latitude, $longitude) {
    return view('google_maps', ['latitude' => $latitude, 'longitude' => $longitude]);
});

Route::get('/itunes{id}/{key}', 'WebController@iosRedirection');


Route::get('/reset-form/{token}', 'WebController@resetForm');


Route::get('/reset-success', 'WebController@success');


Route::get('/forbidden', function () {
    return view('access_forbidden');
});
Route::get('/link-expired', function () {
    return view('link_expired');
});
Route::get('/google-maps/{latitude}/{longitude}', 'WebController@googleMaps');


Route::group([
    'namespace' => 'Auth',
    'prefix' => 'password'
], function () {
    Route::POST('reset-password', 'PasswordResetController@reset')->name('reset_password');

});
