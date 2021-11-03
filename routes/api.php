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



               ///////                 Admin                ////////

// call last prices of all metals from provider ( metal_api )
Route::get('/saveLastPrice',[AdminController::class,'saveLastPrice']);

// call all prices of gold for a period of time like year/month
Route::get('/histPriceGold',[AdminController::class,'histPriceGold']);

// call all prices of silver for a period of time like year/month
Route::get('/histPriceSilver',[AdminController::class,'histPriceSilver']);

// call all prices of platinum for a period of time like year/month
Route::get('/histPricePlatinum',[AdminController::class,'histPricePlatinum']);

// send greater alert to user
Route::post('/sendGreaterAlert',[AdminController::class,'sendGreaterAlert']);

// send less alert to user
Route::post('/sendLessAlert',[AdminController::class,'sendLessAlert']);




             ///////                  Show ( chart )                ////////

// get last price for metals ( GOLD , SELVER , PLATINUM )
Route::get('/getLastprice',[ShowController::class,'getLastprice']);

// get all prices for GOLD during a specific period
Route::get('/histgold',[ShowController::class,'HistoricalGold']);

// get all prices for PLATINUM during a specific period
Route::get('/histsilver',[ShowController::class,'HistoricalSilver']);

// get all prices for SELVER during a specific period
Route::get('/histplatinum',[ShowController::class,'HistoricalPlatinum']);


Route::get('/his/{metalName}',[ShowController::class,'getPrice']);           ////////                  User                 /////////

// save token from user device
Route::post('/getToken',[UserController::class,'getToken']);

// save price from user
Route::post('/userPrice',[UserController::class,'userPrice']);
