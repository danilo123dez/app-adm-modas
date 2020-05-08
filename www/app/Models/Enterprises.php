<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprises extends Model
{

    protected $fillable = ['nome'];

    public function Customer(){
        return $this->hasMany('App\Models\Customer', 'empresa_id');
    }

    public function Stores(){
        return $this->hasMany('App\Models\Stores', 'empresa_id');
    }

    public function Releases(){
        return $this->hasManyThrough('App\Models\Releases', 'App\Models\Stores', 'empresa_id', 'loja_id', 'id', 'id');
    }

}
