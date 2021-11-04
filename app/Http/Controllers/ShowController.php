<?php

namespace App\Http\Controllers;

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
        $metalgold = Metal::select('metalName','metalPrice','date')->latest()->take(3)->get();

        if ($metalgold)
            return $metalgold ;

        else
            return 'error result' ;
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










}
