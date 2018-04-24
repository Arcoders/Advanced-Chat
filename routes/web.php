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

    Route::prefix('list')->group(function () {

        Route::get('/chats', 'ChatsController@chats');

    });

    // ----------------------------------------------------------

    Route::prefix('notifications')->group(function () {

        Route::get('/count', 'NotificationsController@count');
        Route::get('/all', 'NotificationsController@all');
        Route::get('/mark_as_read', 'NotificationsController@markAsRead');

    });

    // ----------------------------------------------------------

    Route::prefix('access_box')->group(function () {

        Route::get('/friend_chat/{user}', 'FriendshipsController@userForChat')->middleware('isFriend');
        Route::get('/group_chat/{group_id}', 'GroupsController@groupForChat')->middleware('groupMember');

    });

    // ----------------------------------------------------------

    Route::prefix('messages')->group(function () {

        Route::get('/latest/{room_name}/{chat_id}', 'MessagesController@latest');
        Route::get('/typing/{room_name}/{chat_id}', 'MessagesController@typing');
        Route::Post('/send', 'MessagesController@send');

    });

    // ----------------------------------------------------------

    Route::prefix('online')->group(function () {

        Route::get('/connected/{room_name}/{chat_id}', 'OnlineController@connected');
        Route::get('/disconnect/{room_name}/{chat_id}', 'OnlineController@disconnect');

    });

    // ----------------------------------------------------------

});

