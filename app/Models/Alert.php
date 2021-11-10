<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
      'metalName' , 'price' ,'currency' ,'type' , 'user_id'
    ];



    protected $hidden = [
         'created_at' ,'updated_at' //  hidden to get method 'ShowController@getArea'
    ];


    protected $appends = ['push'];


    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }


    public function getPushAttribute()
    {
        $type = $this->type;
        $price = $this->price;
        $currency = $this->currency; //Pound
        $metalname  = strtolower($this->metalName);
        $metal_code = config('yaffet.metal_codes')[$metalname];
        $current_price = Metal::where('metalName',  $metal_code )->latest()->first();
        $curr =  Currency::where('currency_code' , config('yaffet.currency_codes')[$currency])->latest()->first();


        if($type == 'less'){
            return ($current_price->metalPrice * $curr['price_rate'] <= $price) ? $metalname." price is greater than your alert price ".$price . ' ' . $currency: null;
        }else{
            return ($current_price->metalPrice * $curr['price_rate'] >= $price) ? $metalname." price is less than your alert price ".$price . ' ' . $currency: null;
        }

    }


}
