<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $fillable = ['nome', 'comissao', 'empresa_id'];

    public function Releases(){
        return $this->hasMany('App\Models\Releases', 'loja_id');
    }

    public function Enterprise() {
        return $this->belongsTo('App\Models\Enterprises', 'empresa_id', 'id');
    }
}
