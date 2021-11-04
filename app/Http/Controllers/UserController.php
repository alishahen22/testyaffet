<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Metal;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use GeneralTrait;


    // get token from user device
    public function getToken(Request $request)
    {

        if (empty($request->deviceToken)  || User::where('deviceToken', $request->input('deviceToken'))->exists() ) {
            return $this->returnData('userId',null,'can\'t add this token','404');
        }

            $user = User::create([
                'deviceToken' => $request->input('deviceToken'),
            ]);

            return $this->returnData('userId',$user->id,'pass to get token','201');

    }



    // save price of user
    public function userPrice(Request $request)
    {
        if(empty($request->metalName)){
            return "empty metal name";
        }

//        $metalName = ($request->input('metalName')=='GOLD')?"XAU":($request->input('metalName')=='SILVER')?"XAG":
//            ($request->input('metalName')=='PLATINUM')?"XPT":"";


        if ($request->input('metalName')=='GOLD')
        {
            $metalName = 'XAU';
        }
        elseif($request->input('metalName')=='SILVER')
        {
            $metalName = 'XAG';
        }
        elseif($request->input('metalName')=='PLATINUM')
        {
            $metalName = 'XPT';
        }



        $metal = Metal::where('metalName', $metalName)->latest()->first();

        $type = 'less';
        if($request->input('price') >  $metal->metalPrice){
            $type = 'greater';

        }
        $alert = Alert::create([
            'price' => $request->input('price'),
            'metalName' => $request->input('metalName'),
//            'type' => ($request->input('price') >  $metal->metalPrice) ? "greater" : "less",
            'type'=>$type,
            'user_deviceToken' => $request->input('user_deviceToken'),
        ]);
        return $this->returnSuccessMessage('done','201');


    }











}
