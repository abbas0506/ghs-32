<?php

namespace App\Policies;

use App\Models\Test;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasAnyRole(['head', 'admin', 'teacher']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Test $test): bool
    {
        //
        return $user->hasAnyRole(['head', 'admin', 'teacher']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasAnyRole(['head', 'admin', 'teacher']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Test $test): bool
    {
        //
        if ($user->hasAnyRole(['head', 'admin'])) return true;
        return $user->hasRole('teacher') && $test->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Test $test): bool
    {
        //
        if ($user->hasAnyRole(['head', 'admin'])) return true;
        return $user->hasRole('teacher') && $test->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Test $test): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Test $test): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }
    // lock
    public function lock(User $user, Test $test): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }
    // unlock
    public function unlock(User $user, Test $test): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }
}
