<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Releases extends Model
{
    protected $fillable = [
        'boleta',
        'romaneio',
        'cliente',
        'data_compra',
        'data_vencimento',
        'valor',
        'loja_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data_compra' => 'date:Y/m/d',
        'data_vencimento' => 'date:Y/m/d',
    ];

    public function Stores() {
        return $this->belongsTo('App\Models\Store', 'loja_id', 'id');
    }
}
