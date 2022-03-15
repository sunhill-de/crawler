<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('index');
});

Route::get('/classes/list', [ClassesController::class, 'list']);
Route::get('/classes/show/{class}', [ClassesController::class, 'show']);

Route::get('/objects/list/{class}', [ObjectsController::class, 'list']);
