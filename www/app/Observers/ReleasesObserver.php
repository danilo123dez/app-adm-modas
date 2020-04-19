<?php

namespace App\Observers;

use App\Models\Releases;
use Illuminate\Support\Str;

class ReleasesObserver
{
    public function creating(Releases $Releases){
        if (empty($Releases->uuid)) {
			$Releases->uuid = Str::uuid();
		}
    }
}
