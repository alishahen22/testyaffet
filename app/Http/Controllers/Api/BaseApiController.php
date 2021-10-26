<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Metal;

class BaseApiController extends Controller
{
    use AppResponseTrait;

     // get last metals that stored in db from provider
     public function get_all()
     {
         $metals = Metal::select('metalName','metalPrice','date')->latest()->take(3)->get();
         return $metals ;
     }

     public function HistoricalGold(){

        $gold = Metal::where('metalName','XAU')->get();
        return $this->ApiResponse($gold,'ok',200);
    }

public function HistoricalPlatinum(){

   $platinum = Metal::where('metalName','XPT')->get();
   return $this->ApiResponse($platinum,'ok',200);
}

public function HistoricalSilver(){

   $silver = Metal::where('metalName','XAG')->get();
   return $this->ApiResponse($silver,'ok',200);
}




}
