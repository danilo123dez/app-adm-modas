<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    public function Enterprise() {
        return $this->hasOne('App\Models\Enterprises');
    }

    public function User() {
        return $this->hasOne('App\Models\User');
    }

}
