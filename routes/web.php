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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('/url')->middleware('auth')->name('url.')->group(function(){
    Route::post('/', 'UrlController@create')->name('create');
});





Route::get('/analytics/{short_code}', 'UrlController@showAnalytics')->middleware('auth');

Route::get('/{short_code}', 'UrlController@getUrl');
