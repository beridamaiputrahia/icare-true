<?php

namespace App\Events;

use App\Models\Devotion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevotionApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(public Devotion $devotion) {}
}
