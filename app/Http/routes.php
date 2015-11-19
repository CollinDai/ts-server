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

Route::get('/', function () {
    return view('welcome');
});

Route::get('movie', 'MovieController@index');

Route::get('movie/refresh', 'MovieController@refresh');

Route::get('subtitle/download/{subFileId}', 'SubtitleController@download');

Route::get('subtitle/{imdbId}/{language}', 'SubtitleController@show');
