<?php

namespace App\Policies;

use App\Models\LinkGroup;
use App\Models\User;

class LinkGroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LinkGroup $linkGroup): bool
    {
        return $user->isAdmin() || $linkGroup->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LinkGroup $linkGroup): bool
    {
        return $user->isAdmin() || $linkGroup->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LinkGroup $linkGroup): bool
    {
        return $user->isAdmin() || $linkGroup->user_id === $user->id;
    }
}
