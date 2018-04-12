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

        Route::get('/check/{user_id}', 'FriendshipsController@check');
        Route::post('/add/{user_id}', 'FriendshipsController@add');
        Route::patch('/accept/{user_id}', 'FriendshipsController@accept');
        Route::delete('/reject/{user_id}', 'FriendshipsController@reject');

    });

    // ----------------------------------------------------------

    Route::prefix('groups')->group(function () {

        Route::get('/all', 'GroupsController@groups');
        Route::patch('/delete/{group}', 'GroupsController@delete');
        Route::patch('/restore/{group}', 'GroupsController@restore');

        Route::get('/group/{group}', 'GroupsController@group');
        Route::post('/edit/{group}', 'GroupsController@edit');

        Route::get('/friends', 'GroupsController@friends');
        Route::post('/create', 'GroupsController@create');

    });

    // ----------------------------------------------------------

});

