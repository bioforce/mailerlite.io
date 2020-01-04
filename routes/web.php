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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/subscribe', function () {
    return view('pages.subscribe');
});

/**
 * Routes for admin panel Subscribers and Additional fields
 */

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'admin'], function () {

        Route::get('subscribers', 'HomeController@subscribers')->name('admin.subscribers');
        Route::get('fields', 'HomeController@fields')->name('admin.fields');
        
    });
});