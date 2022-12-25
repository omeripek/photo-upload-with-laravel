<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('my-photos')->namespace('API')->group(function () {
    Route::post('/', 'PhotoController@add');
    Route::delete('/{photo}', 'PhotoController@remove');
    Route::put('/order', 'PhotoController@order');
    Route::get('/', 'PhotoController@list');
    Route::post('/store', 'PhotoController@store')->name('photos.store');
});
