<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\PriceController;

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


Route::get('/price',[BaseApiController::class,'get_all']);


Route::get('/histgold',[BaseApiController::class,'HistoricalGold']);

Route::get('/histplatinum',[BaseApiController::class,'HistoricalPlatinum']);

Route::get('/histsilver',[BaseApiController::class,'HistoricalSilver']);

