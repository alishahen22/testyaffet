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
use PhpParser\Node\Stmt\Continue_;

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
    public function userPrice(Request $request)
    {    ////////////////////// validation ///////////////////
        $validation = Validator::make($request->all(), [
            'metalName' => [
                'required',
                Rule::in(['GOLD', 'PLATINUM', 'SILVER']),],
            'price' => 'required|numeric',
            'currency' => [
                    'required',
                    Rule::in(['USA Dollar','British pound', 'Euro','Yen','Egyptian pound','Saudi riyal', 'Saudi riyal','Kuwaiti dinar','Omani rial','UAE dirham','Qatari riyal', 'Australian dollar','Canadian dollar']),],
            'user_deviceToken' => 'required'
        ]);

        if ($validation->fails()) {

            return $this->returnData('price', "error", "invalid Price , metalName or currency", '404');


            }
                                    /////////////////////////////
       // $metalName = ($request->input('metalName')=='GOLD')?"XAU":($request->input('metalName')=='SILVER')?"XAG":
         //  ($request->input('metalName')=='PLATINUM')?"XPT":"";
         if ($request->input('metalName') == 'GOLD') {
            $metalName = 'XAU';
        } elseif ($request->input('metalName') == 'SILVER') {
            $metalName = 'XAG';
        } elseif ($request->input('metalName') == 'PLATINUM') {
            $metalName = 'XPT';
        }

            $metal = Metal::where('metalName', $metalName)->latest()->first();
            $curr = Currency::where('currency_code', config("yaffet.currency_codes")[$request->input('currency')])->latest()->first();
            $user = User::where('deviceToken', $request->input('user_deviceToken'))->first();
                if (empty($curr)||empty($metal->metalPrice)) {
                    $this->returnData('price', "error", "There is table empty", '404');
                }
                if (empty($user)) {
                    $this->returnData('price', "error", "the user_deviceToken is not in user token", '404');
                }

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


