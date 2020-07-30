<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
//    public function userr(){
//        return $this->hasOne('App\Userr','user_id');
//    }
    public function userr(){
        return $this->belongsTo('App\Userr');
    }
}
