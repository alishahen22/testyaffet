<?php

namespace App\Jobs;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;



class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $metals = alert::get();

        foreach ($metals as $metal){

            if($metal->push != null)
            {

                $this->sendNotification($metal->user->deviceToken ,$metal->push );
            }
        }
    }



    public function sendNotification($tokengreater , $message)
    {

//        $SERVER_API_KEY = 'AAAA5KHt4DI:APA91bEQ4vjQacVPco7mq_rmnSfD7JSu9ZpwGnBhWE0cYq-QxPXPDbZOmFkM-c7jaC9lWPwb0hry9L3yh7qG0kNrM_Dt-aBV85qwqerXA5q5GImKiqFaogMvKvLtSHcmei2YkD_Qp4Tv';

//        if(!empty($tokengreater))
//        {

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

    }


}
