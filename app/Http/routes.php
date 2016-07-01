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

Route::get('/test', function ()
{
    return view('welcome');
});

Route::post('/upload', [
    'as' => 'image.upload',
    'uses' => '\App\Http\Controllers\ImageController@store'
]);

Route::get('/image/{id}', [
    'as' => 'image.show',
    'uses' => '\App\Http\Controllers\ImageController@show'
])->where('id', '[0-9]+');

Route::get('/exec', [
    'uses' => '\App\Http\Controllers\TestController@exec'
]);

Route::get('/path', function()
{
   $s = '{"job":"Illuminate\\Queue\\CallQueuedHandler@call","data":{"commandName":"App\\Jobs\\GenerateImage","command":"O:22:\"App\\Jobs\\GenerateImage\":8:{s:8:\"\u0000*\u0000image\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":2:{s:5:\"class\";s:16:\"App\\Models\\Image\";s:2:\"id\";i:1;}s:10:\"\u0000*\u0000options\";a:2:{s:6:\"colors\";b:0;s:5:\"style\";s:1:\"1\";}s:9:\"\u0000*\u0000styles\";a:3:{i:1;s:28:\"\/var\/app\/neural\/style\/12.jpg\";i:2;s:5:\"2.jpg\";i:3;s:5:\"3.jpg\";}s:7:\"\u0000*\u0000size\";s:3:\"500\";s:10:\"connection\";N;s:5:\"queue\";N;s:5:\"delay\";N;s:6:\"\u0000*\u0000job\";N;}"}}';
    return unserialize($s);
});