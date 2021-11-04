<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use App\Mail\SendNotification;
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
        $metalcode = config('yaffet.metal_codes')[$metalName] ;

        if (!in_array($metalName , $metal_names))
        {
            return $this->returnError('404','invalid metals');
        }

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
        $metals = alert::get();

        foreach ($metals as $metal){

            if($metal->push != null)

            {
                $this->sendNotification($metal->user_deviceToken ,$metal->push );
                Alert::where('id', $metal->id)->delete();
            }
        }
    }


    public function sendNotification($tokengreater , $message)
    {


        $SERVER_API_KEY  = config('yaffet.fcm_api_key');
            $data = [

                "registration_ids" =>   [$tokengreater] ,

                "notification" => [

                    "title" => 'alert : ',

                    "body" => $message,

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

            return true;

}



//    public function sendEmail()
//    {
//        $notification = (new SendNotificationJob())->delay(Carbon::now()->addSeconds(1));
//        dispatch($notification);
//        return 'alert sent';
//    }



}
