<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
      'metalName' , 'price' ,'currency' ,'type' , 'user_deviceToken'
    ];



    protected $hidden = [
         'created_at' ,'updated_at' //  hidden to get method 'ShowController@getArea'
    ];


    protected $appends = ['push'];


    public function user()
    {
        return $this->belongsTo('App\Models\User','user_token');
    }


    public function getPushAttribute()
    {
        $type = $this->type;
        $price = $this->price;
        $metalname  = strtolower($this->metalName);
        $metal_code = config('yaffet.metal_codes')[$metalname];
        $current_price = Metal::where('metalName',  $metal_code )->latest()->first();

        if($type == 'less'){
            return ($current_price->metalPrice <= $price) ? $metalname." price is greater than your alert price ".$price : null;
        }else{
            return ($current_price->metalPrice >= $price) ? $metalname." price is less than your alert price ".$price : null;
        }

    }

}
