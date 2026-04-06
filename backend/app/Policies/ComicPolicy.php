<?php

namespace App\Policies;

use App\Models\Comic;
use App\Models\User;

class ComicPolicy
{
    /**
     * Determine if the user can view the comic
     */
    public function view(User $user, Comic $comic): bool
    {
        // Super admin can view all
        if ($user->role === 'super_admin') {
            return true;
        }

        // Org-scoped check
        return $user->org_id === $comic->org_id && $comic->status === 'published';
    }

    /**
     * Determine if the user can update the comic
     */
    public function update(User $user, Comic $comic): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return in_array($user->role, ['org_admin', 'cms_editor']) && 
               $user->org_id === $comic->org_id;
    }

    /**
     * Determine if the user can delete the comic
     */
    public function delete(User $user, Comic $comic): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return in_array($user->role, ['org_admin']) && 
               $user->org_id === $comic->org_id;
    }
}
