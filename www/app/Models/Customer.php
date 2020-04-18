<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

  protected $fillable = ['email', 'cpf', 'nome', 'empresa_id'];

    public function Enterprise() {
        return $this->hasOne('App\Models\Enterprises', 'id', 'empresa_id');
    }

	/**
	 * Get all of the administrator's users.
	 */
  public function user()
  {
    return $this->morphOne(User::class, 'loginable');
  }

}
