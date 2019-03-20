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

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', function() {
        return view('chat');
    });

    Route::get('/getUserLogin', function() {
        return Auth::user();
    });

    Route::get('/messages', function() {
        return App\Message::with('user')->get();
    });

    Route::post('/messages', function() {
        $user = Auth::user();
        $message = $user->messages()->create([
            'message' => request()->get('message')
        ]);
        broadcast(new App\Events\MessagePosted($message, $user))->toOthers();

        return ['status' => 'OK'];
    });

    Route::get('/chat2', function() {
        return view('chat2');
    });

    Route::get('/chat2/messages', 'ChatController@index');
    Route::post('/chat2/messages', 'ChatController@store');
});
