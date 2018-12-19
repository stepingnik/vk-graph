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

Route::get('/one', 'MainController@index');
Route::post('/one', 'MainController@one');
Route::get('/two', 'MainController@index2');
Route::post('/two', 'MainController@two');

Route::post('/test', 'MainController@test');
Route::get('/test', 'MainController@indextest');

Route::get('/ajax', 'AjaxController@ajax');
//Route::post('/ajax', 'AjaxController@ajax');