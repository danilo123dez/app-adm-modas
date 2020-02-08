<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Releases extends Model
{
    public function Stores() {
        return $this->belongsTo('App\Models\Store');
    }
}
