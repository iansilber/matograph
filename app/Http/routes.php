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

Route::get('/', "MainController@index");
Route::get('/details', "MainController@details");
Route::post('/get_movie_details', "MainController@getMovieDetails");
Route::get('/suggestions', "MainController@suggestions");
Route::get('/imdb_poster', "MainController@imdbPoster");
