<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
      'metalName' , 'price' , 'type' , 'user_id'
    ];



    protected $hidden = [
         'created_at' ,'updated_at' //  hidden to get method 'ShowController@getArea'
    ];




    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }


}
