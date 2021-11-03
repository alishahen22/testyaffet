<?php

namespace App\Http\Controllers;

use App\Models\Metal;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ShowController extends Controller
{

    use GeneralTrait ;


    // get last price for metals ( GOLD , SELVER , PLATINUM )
    public function getLastprice()
    {
        $metals = Metal::select('metalName','metalPrice','date')->latest()->take(3)->get();
        return $metals ;
    }


    // get all prices for GOLD during a specific period
    public function HistoricalGold()
    {
        $gold = Metal::where('metalName','XAU')->get();
        return $this->returnData('gold',$gold,'There are all Prices of Gold for a period time','201');
    }


    // get all prices for SELVER during a specific period
    public function HistoricalSilver()
    {
        $silver = Metal::where('metalName','XAG')->get();
        return $this->returnData('silver',$silver,'There are all Prices of Silver for a period time','201');
    }


    // get all prices for PLATINUM during a specific period
    public function HistoricalPlatinum()
    {
        $platinum = Metal::where('metalName','XPT')->get();
        return $this->returnData('platinum',$platinum,'There are all Prices of Platinum for a period time','201');
    }


    public function getPrice($metalName)
    {
        $metal_names = config('yaffet.metal_name');
        if(!in_array($metalName , $metal_names)){
           return 'invalid metal name';
        }
        $code = config('yaffet.metal_codes')[$metalName];

        $result = Metal::where('metalName',$code)->get();
        return $this->returnData($metalName,$result,'There are all Prices of '.$metalName.' for a period time','201');

    }










}
