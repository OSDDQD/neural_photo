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

Route::post('/upload', [
    'as' => 'image.upload',
    'uses' => '\App\Http\Controllers\ImageController@store'
]);

Route::get('/image/{id}', [
    'as' => 'image.show',
    'uses' => '\App\Http\Controllers\ImageController@show'
])->where('id', '[0-9]+');