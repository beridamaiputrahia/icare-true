<?php

namespace App\Events;

use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PointEarned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User      $user,
        public UserPoint $point
    ) {}
}
