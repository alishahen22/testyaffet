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
        $user = User::create([
            'deviceToken' => $request->input('deviceToken'),
        ]);

        return $this->returnData('userId',$user->id,'pass to get token','201');
    }



    // save price of user
    public function userPrice(Request $request)
    {
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

        if ($request->input('price') >  $metal->metalPrice)
        {
            $alert = Alert::create([
                'price' => $request->input('price'),
                'metalName' => $request->input('metalName'),
                'type' => 'greater',
                'user_id' => $request->input('user_id'),
            ]);
        }

        else
            {
            $alert = Alert::create([
                'price' => $request->input('price'),
                'metalName' => $request->input('metalName'),
                'type' => 'less' ,
                'user_id' => $request->input('user_id'),
            ]);
        }

    }











}
