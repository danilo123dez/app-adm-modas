<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprises extends Model
{

    public function Customer(){
        return $this->hasMany('App\Models\Customer', 'empresa_id');
    }

    public function Stores(){
        return $this->hasMany('App\Models\Stores');
    }

}
