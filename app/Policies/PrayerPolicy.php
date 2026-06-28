<?php

namespace App\Policies;

use App\Models\Prayer;
use App\Models\User;

class PrayerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Prayer $prayer): bool
    {
        if ($user->isAdmin()) return true;
        // Users can only see their own prayers or approved/answered ones
        return $prayer->user_id === $user->id || in_array($prayer->status, ['approved', 'answered']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Prayer $prayer): bool
    {
        if ($user->isAdmin()) return true;
        return $prayer->user_id === $user->id && $prayer->status === 'pending';
    }

    public function delete(User $user, Prayer $prayer): bool
    {
        if ($user->isAdmin()) return true;
        return $prayer->user_id === $user->id;
    }

    public function approve(User $user, Prayer $prayer): bool
    {
        return $user->isAdmin();
    }

    public function markAnswered(User $user, Prayer $prayer): bool
    {
        return $user->isAdmin() && $prayer->status === 'approved';
    }
}
