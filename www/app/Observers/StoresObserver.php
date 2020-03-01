<?php

namespace App\Observers;

use App\Models\Stores;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoresObserver
{
    public function creating(Stores $store){
        if (empty($store->uuid)) {
			$store->uuid = Str::uuid();
        }
    }
}
