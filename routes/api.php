<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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



             ///////                  Show ( chart )                ////////

// get last price for metals ( GOLD , SELVER , PLATINUM )
Route::get('/getLastprice',[ShowController::class,'getLastprice']);

// get historical price for all metals
Route::get('/getHistPrice/{metalName}',[ShowController::class,'getHistPrice']);           ////////                  User                 /////////

// save token from user device
Route::post('/getToken',[UserController::class,'getToken']);

// save price from user
Route::post('/userPrice',[UserController::class,'userPrice']);

// get last Currency
Route::get('/getLastCurrency',[ShowController::class,'getLastCurrency']);
