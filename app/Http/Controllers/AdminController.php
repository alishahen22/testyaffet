<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use App\Mail\SendNotification;
use App\Models\Alert;
use App\Models\Currency;
use App\Models\Metal;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
     use GeneralTrait ;


    // call last prices of all metals from provider ( metal_api )
    public function saveLastPrice()
    {

        $response = Http::get(config('yaffet.saveLastMetals'));
        $manage = json_decode($response, true);

        if($manage['success']==true)
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
            return $this->returnSuccessMessage('Metals saved successfully','201');
        }
        else
        {
            return $this->returnError('404','there is no response from provider');
        }

    }


    // call all prices of metals for a period of time like year/month
    public function saveHistMetals($metalName)
    {
        $metal_names = config('yaffet.metal_name');

        if (!in_array($metalName , $metal_names))
        {
            return $this->returnError('404','invalid metals');
        }
        $metalcode = config('yaffet.metal_codes')[$metalName] ;
        $response = Http::get(config('yaffet.saveHistoricalMetals').$metalcode);
        $metal = json_decode($response, true);


        if($metal['success'])
        {
            foreach ($metal['rates'] as $key => $value)
            {
                $metal = new Metal;
                $metal->metalPrice = 1/$value[$metalcode];
                $metal->date = $key." 12:00:00";
                $metal->metalName = $metalcode;
                $metal->save();
            }
        }
        else
        {
          return $this->returnError('404','error api');
        }

    }



    public function handleSendNotification()
    {
                $metals = Alert::chunk(50,function ($alerts)
                {
                    dispatch(new SendNotificationJob($alerts));
                    return "done";
                });
    }


    // call prices for all countries


    public function saveLastCurrency()
    {

        $response = Http::get(config('yaffet.saveLastCurrency'));
        $manage = json_decode($response, true);

        if($manage['success'])
        {

            foreach($manage['quotes'] as $key => $value)
            {
                Currency::create([
                    'currency_code' => substr($key, -3),
                    'price_rate' => $value ,
                ]);
            }
            return $this->returnSuccessMessage('currency saved successfully','201');
        }
        else
        {
            return $this->returnError('404','there is no response from provider');
        }

    }


}
