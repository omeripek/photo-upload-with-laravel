<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/my-photos', 'App\Http\Controllers\PhotoController@index');
//Route::get('/photos/create', 'PhotoController@create')->name('photos.create');
//Route::post('/my-photos/add', 'App\Http\Controllers\PhotoController@add')->name('photos.add');
//Route::delete('/my-photos/{photo}', 'PhotoController@destroy')->name('photos.destroy');
//Route::post('/my-photos/{photo}/update-order', 'PhotoController@updateOrder')->name('photos.update-order');

Route::get('/my-photos', 'App\Http\Controllers\PhotoController@index')->name('photos.index');
Route::post('/my-photos/order', 'App\Http\Controllers\PhotoController@updateOrder')->name('photos.order');

Route::delete('my-photos/{photo}', 'App\Http\Controllers\PhotoController@destroy')->name('photos.destroy');
Route::post('/my-photos', 'App\Http\Controllers\PhotoController@store')->name('photos.store');
