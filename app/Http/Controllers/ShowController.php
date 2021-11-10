<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Metal;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{

    use GeneralTrait ;


    // get last price for metals ( GOLD , SELVER , PLATINUM )
    public function getLastprice()
    {
        $metalgold = Metal::select('metalName','metalPrice','date')->where('metalName','XAU')->orderBy('date','desc')->first();
        $metalsilver = Metal::select('metalName','metalPrice','date')->where('metalName','XAG')->orderBy('date','desc')->first();
        $metalplatinum = Metal::select('metalName','metalPrice','date')->where('metalName','XPT')->orderBy('date','desc')->first();

        return $this->return3Data('metalgold',$metalgold,'metalsilver',$metalsilver,
            'metalplatinum',$metalplatinum);
    }


    // get historical price for all metals
    public function getHistPrice($metalName)
    {
        $metal_names = config('yaffet.metal_name');
        if(!in_array($metalName , $metal_names)){
           return 'invalid metal name';
        }
        $code = config('yaffet.metal_codes')[$metalName];

        $result = Metal::where('metalName',$code)->get();
        return $this->returnData($metalName,$result,'There are all Prices of '.$metalName.' for a period time','201');

    }




public function getLastCurrency()
{
   $lastCurrency =  Currency::select('currency_code','price_rate')->latest()->get()->unique('currency_code');
   return $lastCurrency;
}





}
