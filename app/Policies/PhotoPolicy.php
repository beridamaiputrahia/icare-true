<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;

class PhotoPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Photo $photo): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function delete(User $user, Photo $photo): bool { return $user->isAdmin() || $photo->user_id === $user->id; }
}
