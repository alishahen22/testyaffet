<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateRequest;
use App\Models\Alert;
use App\Models\Currency;
use App\Models\Metal;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use GeneralTrait;


    // get token from user device
    public function getToken(Request $request)
    {

        if (empty($request->deviceToken) || User::where('deviceToken', $request->input('deviceToken'))->exists()) {
            return $this->returnData('userId', null, 'can\'t add this token', '404');
        }

        $user = User::create([
            'deviceToken' => $request->input('deviceToken'),
        ]);

        return $this->returnData('userId', $user->id, 'pass to get token', '201');

    }



    // get token from user device
    public function userPrice(ValidateRequest $request)
    {
        $validation = Validator::make($request->all(), [
            'metalName' => [
                'required',
                Rule::in(['GOLD', 'PLATINUM', 'SILVER']),],
            'price' => 'required|numeric',
            'currency' => [
                    'required',
                    Rule::in(['Dollar', 'Pound', 'Euro','Yen','EGP','SAU','KWT','OMN','UAE','QAT']),],
            'user_deviceToken' => 'required'
        ]);


        if ($validation->fails()) {

            return $this->returnData('price', "error", "invalid Price , metalName or currency", '404');


            if (empty($request->metalName)) {
                return "empty metal name";
            }}

        $metalName = ($request->input('metalName')=='GOLD')?"XAU":($request->input('metalName')=='SILVER')?"XAG":
            ($request->input('metalName')=='PLATINUM')?"XPT":"";

            $metal = Metal::where('metalName', $metalName)->latest()->first();
            $curr = Currency::where('currency_code', config("yaffet.currency_codes")[$request->input('currency')])->latest()->first();
            $user = User::where('deviceToken', $request->input('user_deviceToken'))->first();

            $type = 'less';
            if ($request->input('price') > $metal->metalPrice * $curr['price_rate']) {
                $type = 'greater';

            }
            $alert = Alert::create([
                'price' => $request->input('price'),
                'metalName' => $request->input('metalName'),
                'currency' => $request->input('currency'),
                'type' => $type,
                'user_id' => $user->id,
            ]);
            return $this->returnSuccessMessage('done', '201');

        }


    }


