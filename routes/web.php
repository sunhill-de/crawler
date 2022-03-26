<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ObjectsController;

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

Route::post('/api/objectSearch', [ApiController::class, 'objectSearch']);

Route::get('/classes/list', [ClassesController::class, 'list']);
Route::get('/classes/show/{class}', [ClassesController::class, 'show']);

Route::get('/objects/list/{class}/{page?}', [ObjectsController::class, 'list']);
Route::get('/objects/add/{class}', [ObjectsController::class, 'add']);
Route::post('/objects/add/{class}', [ObjectsController::class, 'exec_add']);
Route::get('/objects/edit/{objectid}', [ObjectsController::class, 'edit']);
