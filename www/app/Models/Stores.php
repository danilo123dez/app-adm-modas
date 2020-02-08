<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{

    public function Releases(){
        return $this->hasMany('App\Models\Releases');
    }

    public function Enterprise() {
        return $this->belongsTo('App\Models\Enterprises');
    }
}
