<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', [
    'as' => 'home',
    'uses' => 'Controller@getHome'
]);

Route::get('/submit_participation', [
    'as' => 'home',
    'uses' => 'Controller@getHome'
]);

Route::get('/uploadcode', [
    'as' => 'uploadcode',
    'uses' => 'Controller@getUploadPage'
]);

Route::get('/submit_user', [
    'as' => 'home',
    'uses' => 'Controller@getHome'
]);

Route::get('/checkqr', [
    'as' => 'home',
    'uses' => 'Controller@getHome'
]);

Route::get('/cron_winner', 'Controller@pickWinner' );

Route::post('/checkqr', 'Controller@checkQr');

Route::post('/submit_user', 'Controller@submit_user');

Route::post('/submit_participation', 'Controller@submit_participation');

Route::get('/userinformation', 'Controller@submit_user' );

Route::get('/testmail', 'Controller@sendConfirmMail' );

Route::get('/randomstr', 'Controller@addcodes' );

Route::get('/winners', 'Controller@getWinners' );
