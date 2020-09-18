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


Route::get('/phpinfo', function () {
    return phpinfo();
});

Route::get('/post', 'PostController@index');
Route::get('/post/create', 'PostController@create');
Route::post('/post', 'PostController@store');
Route::get('/post/{id}', 'PostController@edit');
Route::put('/post/{id}', 'PostController@update');
Route::delete('/post/{id}', 'PostController@destroy');

Route::get('/Attachment/{id}', 'AttachmentController@show');

Route::resource('cast', 'TagController', ['except' => ['show']]);
Route::resource('genre', 'TagController', ['except' => ['show']]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
