<?php

namespace App\Policies;

use App\Enum\RoleName;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     Any user (even guest can view a post)
     */
    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Only authenticated users can create
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the post owner can update
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Owner or admin can delete
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id ||
            $user->roles()->where('name', RoleName::Admin->value)->exists();
    }

    // Only admin can publish
    public function publish(User $user, Post $post): bool
    {
        return $user->roles()->where('name', RoleName::Admin->value)->exists();
    }
}
