<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users (only administrators).
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view a user's profile.
     */
    public function view(?User $user, User $model)
    {
        // Anyone can view user profiles
        return true;
    }

    /**
     * Determine whether the user can update their profile.
     */
    public function update(User $user, User $model)
    {
        // Users can update their own profile or administrators can update any profile
        return $user->user_id === $model->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete their profile.
     */
    public function delete(User $user, User $model)
    {
        // Users can delete their own profile or administrators can delete any profile
        return $user->user_id === $model->user_id || $user->is_admin;
    }
}
