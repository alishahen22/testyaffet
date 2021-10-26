<?php

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\PriceController;
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
Route::get('/price',[PriceController::class,'save_data']);

Route::get('/getlastprice',[PriceController::class,'get_all']);

 Route::get('/historicalPrice',[PriceController::class,'historicalPrice']);



