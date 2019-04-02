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

Route::get('thumbnail', 'thumbnail@handle');
Route::get('thumbnaill', 'thumbnail@thumbnail');
Route::get('getThum', 'thumbnail@getThum');
Route::post('postThum','thumbnail@postThum')->name('postThum');
Route::get('store', 'thumbnail@getT');


Route::get('cutVideo', 'thumbnail@cutVideo');