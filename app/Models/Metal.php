<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metal extends Model
{
    use HasFactory;

    protected $fillable = [
       'metalName' , 'metalPrice' , 'date'
    ];


    protected $appends = ['name'];


    public function getNameAttribute(){
        if($this->metalName == 'XAU'){
            return 'GOLD';
        }elseif ($this->metalName == 'XAG'){
            return 'SELVER';
        }elseif($this->metalName == 'XPT'){
            return 'PLATINUM';
        }else{
            return $this->metalName;
        }


    }


}
