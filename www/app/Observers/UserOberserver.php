<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserOberserver
{
    public function creating(User $user){
        if (empty($user->uuid)) {
			$user->uuid = Str::uuid();
        }
        $user->password = Hash::make($user->password);
    }
}
