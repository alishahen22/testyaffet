<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Metal;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
     use GeneralTrait ;


    // call last prices of all metals from provider ( metal_api )
    public function saveLastPrice()
    {

        $response = Http::get('https://metals-api.com/api/latest?access_key=t7mpy4a8dyxccmp5v9q3k5xiw67nnrd8z788nu32art8og5l90izl07i9vld');
        $manage = json_decode($response, true);

        if($response)
        {
            $system_metals = config('app.metals');
            foreach($manage['rates'] as $key => $value)
            {
                $timestamp=$manage['timestamp'];

                if(in_array($key , $system_metals ))
                {
                    Metal::create([
                        'metalName' => $key,
                        'metalPrice' => 1/$value ,
                        'date'=> gmdate("Y-m-d H:i:s", $timestamp)

                    ]);
                }
            }
        }

        else
        {
            return $this->returnError('404','there is no response from provider');
        }

    }



    // call all prices of gold for a period of time like year/month
    public function histPriceGold()
    {
        $response = Http::get('https://metals-api.com/api/timeseries?access_key=rrsneuoegle9lc350kkqg9ms2nld4l70q6rdo5nzemangc0pc8nevse155tb&start_date=2020-02-26&end_date=2021-02-24&symbols=XAU');

        if ($response)
        {
            $gold = json_decode($response, true);
            foreach ($gold['rates'] as $key => $value)
            {
                $gold = new Metal;
                $gold->metalPrice = 1/$value['XAU'];
                $gold->date = $key." 12:00:00";
                $gold->metalName = 'XAU';
                $gold->save();
            }
        }
        else
        {
            return $this->returnError('404','error api key or not found response');
        }

    }


    // call all prices of silver for a period of time like year/month
    public function histPriceSilver()
    {
        $response = Http::get('https://metals-api.com/api/timeseries?access_key=rrsneuoegle9lc350kkqg9ms2nld4l70q6rdo5nzemangc0pc8nevse155tb&start_date=2020-02-26&end_date=2021-02-24&symbols=XAG');

        if ($response)
        {
            $silver = json_decode($response, true);
            foreach ($silver['rates'] as $key => $value)
            {
                $silver = new Metal;
                $silver->metalPrice = 1/$value['XAG'];
                $silver->date = $key." 12:00:00";
                $silver->metalName = 'XAG';
                $silver->save();
            }
        }
        else
        {
            return $this->returnError('404','error api key or not found response');
        }

    }


    // call all prices of platinum for a period of time like year/month
    public function histPricePlatinum()
    {
        $response = Http::get('https://metals-api.com/api/timeseries?access_key=rrsneuoegle9lc350kkqg9ms2nld4l70q6rdo5nzemangc0pc8nevse155tb&start_date=2020-02-26&end_date=2021-02-24&symbols=XPT');

        if ($response)
        {
            $platinum = json_decode($response, true);
            foreach ($platinum['rates'] as $key => $value)
            {
                $platinum = new Metal;
                $platinum->metalPrice = 1/$value['XPT'];
                $platinum->date = $key." 12:00:00";
                $platinum->metalName = 'XPT';
                $platinum->save();
            }
        }
        else
        {
            return $this->returnError('404','error api key or not found response');
        }

    }



    // send greater alert to user
    public function sendGreaterAlert()
    {
        $metals =  alert::get();
        foreach ($metals as $metal)
        {

            // get token from gold metal
            $gold = Metal::where('metalName', 'XAU')->latest()->first();
            if ($metal->type == 'greater' AND $metal->metalName=='GOLD')
            {
                if($metal->price <= $gold->metalPrice )
                {
                    $tokengreater[]=$metal->user->deviceToken;
                }
            }

            //  get token from silver metal
            $silver = Metal::where('metalName', 'XAG')->latest()->first();
            if ($metal->type == 'greater' AND $metal->metalName=='SILVER')
            {
                if($metal->price <= $silver->metalPrice )
                {
                    $tokengreater[]=$metal->user->deviceToken;
                }
            }

            // get token from platinum metal
            $plainum = Metal::where('metalName', 'XPT')->latest()->first();
            if ($metal->type == 'greater' AND $metal->metalName=='PLATINUM')
            {
                if($metal->price <= $plainum->metalPrice )
                {
                    $tokengreater[]=$metal->user->deviceToken;
                }
            }

        }


        $SERVER_API_KEY = 'AAAA5KHt4DI:APA91bEQ4vjQacVPco7mq_rmnSfD7JSu9ZpwGnBhWE0cYq-QxPXPDbZOmFkM-c7jaC9lWPwb0hry9L3yh7qG0kNrM_Dt-aBV85qwqerXA5q5GImKiqFaogMvKvLtSHcmei2YkD_Qp4Tv';

        if(!empty($tokengreater))
        {
            $data = [

                "registration_ids" =>   $tokengreater ,

                "notification" => [

                    "title" => 'alert : ',

                    "body" => 'price is high / low',

                    "sound"=> "default" // required for sound on ios

                ],

            ];

            $dataString = json_encode($data);

            $headers = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            return $this->returnData('response',$response,'done','201') ;
        }
        else
        {
            return $this->returnError('404','there is no token');
        }



    }


    // send less alert to user
    public function sendLessAlert()
    {
        $metals = alert::get();
        foreach ($metals as $metal)
        {

            // get token from gold metal
            $gold = Metal::where('metalName', 'XAU')->latest()->first();
            if ($metal->type == 'less' and $metal->metalName == 'GOLD')
            {
                if ($metal->price >= $gold->metalPrice)
                {
                    $tokenless[] = $metal->user->deviceToken;
                }

            }

            //  get token from silver metal
            $silver = Metal::where('metalName', 'XAG')->latest()->first();
            if ($metal->type == 'less' and $metal->metalName == 'SILVER')
            {
                if ($metal->price >= $silver->metalPrice)
                {
                    $tokenless[] = $metal->user->deviceToken;
                }
            }

            // get token from platinum metal
            $plainum = Metal::where('metalName', 'XPT')->latest()->first();
            if ($metal->type == 'less' and $metal->metalName == 'PLATINUM')
            {
                if ($metal->price >= $plainum->metalPrice)
                {
                    $tokenless[] = $metal->user->deviceToken;
                }
            }

        }



        $SERVER_API_KEY = 'AAAA5KHt4DI:APA91bEQ4vjQacVPco7mq_rmnSfD7JSu9ZpwGnBhWE0cYq-QxPXPDbZOmFkM-c7jaC9lWPwb0hry9L3yh7qG0kNrM_Dt-aBV85qwqerXA5q5GImKiqFaogMvKvLtSHcmei2YkD_Qp4Tv';

        if(!empty($tokenless))
        {
            $data = [

                "registration_ids" =>   $tokenless ,

                "notification" => [

                    "title" => 'alert : ',

                    "body" => 'price is high / low',

                    "sound"=> "default" // required for sound on ios

                ],

            ];

            $dataString = json_encode($data);

            $headers = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            return $this->returnData('response',$response,'done','201') ;

        }

        else
        {
            return $this->returnError('404','there is no token');
        }




    }














}
