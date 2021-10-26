<?php

namespace App\Http\Controllers;

use App\Models\alert;
use App\Models\HistoricalPrice;
use App\Models\Metal;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PriceController extends Controller
{
     // call last metals api provider
     public function save_data()
     {
         $response = Http::get('https://metals-api.com/api/latest?access_key=t7mpy4a8dyxccmp5v9q3k5xiw67nnrd8z788nu32art8og5l90izl07i9vld');
         $manage = json_decode($response, true);


         $system_metals = config('app.metals');
         foreach($manage['rates'] as $key => $value)
         {
            $timestamp=$manage['timestamp'];

             if(in_array($key , $system_metals )){
                   Metal::create([
                     'metalName' => $key,
                     'metalPrice' => 1/$value ,
                     'date'=> gmdate("Y-m-d H:i:s", $timestamp)

                 ]);
             }
         }

     }





    public function historicalPrice()
     {
         //get the gold historical price
       $response = Http::get('https://metals-api.com/api/timeseries?access_key=epx1j0329mdhmwjxemr6t7bw2xmxvsnnqlw97985hpcto5r1vq06dvpx218r&start_date=2020-02-26&end_date=2021-02-24&symbols=XAU');
     //  $response = Http::get('https://metals-api.com/api/timeseries?access_key=epx1j0329mdhmwjxemr6t7bw2xmxvsnnqlw97985hpcto5r1vq06dvpx218r&start_date=2021-02-25&end_date=2021-10-24&symbols=XAU');
         $gold = json_decode($response, true);
        foreach ($gold['rates'] as $key => $value) {
                $gold = new Metal;
                $gold->metalPrice = 1/$value['XAU'];
                $gold->date = $key." 12:00:00";
                $gold->metalName = 'XAU';
                $gold->save();
         }

        return('ok');
     }


 }

