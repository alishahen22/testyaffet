<?php

namespace App\Http\Controllers\api;

trait AppResponseTrait {

    public function ApiResponse($data= null,$message = null,$status = null)
    {
        $array = [
            'data'=>$data,
            'message'=>$message,
            'status'=>$status,
        ];

        return response($array,$status);

    }
}
