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
    public $alerts ;
    public function __construct($alerts)
    {
        $this->alerts = $alerts ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->alerts as $alert){
            if($alert->push != null)
            {
                $this->sendNotification($alert->user_deviceToken ,$alert->push );
                Alert::where('id', $alert->id)->delete();
                return 'done' ;
            }
            else
            {
                return 'failed' ;
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

        return "success";

    }


}
