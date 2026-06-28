<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\User;

class AlbumPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Album $album): bool { return $album->is_published || $user->isAdmin(); }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Album $album): bool { return $user->isAdmin(); }
    public function delete(User $user, Album $album): bool { return $user->isAdmin(); }
}
