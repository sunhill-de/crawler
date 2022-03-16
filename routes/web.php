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
Route::get('/css/crawler.css', 'App\Http\Controllers\SystemController@css');
Route::get('/js/crawler.js', 'App\Http\Controllers\SystemController@js');

Route::get('/', function () {
    return view('index');
});
