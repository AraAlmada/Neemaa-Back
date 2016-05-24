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

/*
 * CORS CONFIG
 */
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    Route::post('/register', 'RegisterController@index');
    Route::post('/login', 'LoginController@index');
    Route::post('/recovery', 'LoginController@recovery');
    Route::post('/recovery_password', 'LoginController@recoveryPassword');
    Route::get('/confirm/{token}', 'RegisterController@confirm');
    Route::get('/resend/{email}', 'LoginController@resendMail');
});

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {
    Route::get('/auth', function() {
        return response()->json(['data' => 'user_logged']);
    });
    Route::post('/get-user', 'UserController@getUser');
    Route::put('/update-user', 'UserController@updateUser');
    Route::post('/update-user-to-neemstyler', 'UserController@updateUserToNeemstyler');
    Route::post('/search', 'SearchController@getList');
    Route::post('/get-neemstyler-search', 'SearchController@getNeem');
});

Route::group(['prefix' => 'api', 'middleware' => 'neemstyler'], function () {
    Route::get('/auth/neemstyler', function() {
        return response()->json(['data' => 'neemstyler_logged']);
    });
    Route::get('/get-neemstyler', 'UserController@getNeemStyler');
    Route::post('/update-neemstyler', 'UserController@updateNeemStyler');
});

Route::group(['prefix' => 'api', 'middleware' => 'admin'], function () {
    Route::get('/auth/admin', function() {
        return response()->json(['data' => 'admin_logged']);
    });
});

