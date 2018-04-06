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

Route::get('/', function () { return view('welcome'); });

Auth::routes();


Route::group(['middleware' => ['auth']], function () {

    // ----------------------------------------------------------

    Route::get('/home', 'HomeController@index')->name('home');

    // ----------------------------------------------------------

    Route::prefix('profile')->group(function () {

        Route::get('/users', 'ProfileController@users');
        Route::get('/user/{user}', 'ProfileController@user');
        Route::post('/edit', 'ProfileController@edit');

    });

    // ----------------------------------------------------------

    Route::prefix('friendship')->group(function () {

        Route::get('/chats', 'FriendshipsController@chats');

    });

    // ----------------------------------------------------------

});

