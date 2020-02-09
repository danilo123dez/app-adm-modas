<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserOberserver
{
    public function creating(User $user){
        if (empty($user->uuid)) {
			$user->uuid = Str::uuid();
		}
    }
}
