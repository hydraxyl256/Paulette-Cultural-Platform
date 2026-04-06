<?php

namespace App\Policies;

use App\Models\ChildProfile;
use App\Models\User;

class ChildProfilePolicy
{
    /**
     * Determine if the user can view the child profile
     */
    public function view(User $user, ChildProfile $profile): bool
    {
        // Super admin
        if ($user->role === 'super_admin') {
            return true;
        }

        // Parent can view own children
        if ($user->role === 'parent') {
            return $user->id === $profile->parent_user_id;
        }

        // Teacher/Admin can view org children
        return in_array($user->role, ['teacher', 'org_admin']) && 
               $user->org_id === $profile->org_id;
    }

    /**
     * Determine if the user can update the child profile
     */
    public function update(User $user, ChildProfile $profile): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'parent' && $user->id === $profile->parent_user_id;
    }

    /**
     * Determine if the user can delete the child profile
     */
    public function delete(User $user, ChildProfile $profile): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'parent' && $user->id === $profile->parent_user_id;
    }
}
