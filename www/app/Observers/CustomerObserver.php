<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerObserver
{
    public function creating(Customer $customer){
        if (empty($customer->uuid)) {
			$customer->uuid = Str::uuid();
		}
    }
}
