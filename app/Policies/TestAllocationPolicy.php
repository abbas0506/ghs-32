<?php

namespace App\Policies;

use App\Models\TestAllocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TestAllocationPolicy
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
    public function view(User $user, TestAllocation $testAllocation): bool
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
        return $user->hasAnyRole(['head']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TestAllocation $testAllocation): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TestAllocation $testAllocation): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TestAllocation $testAllocation): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TestAllocation $testAllocation): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }
    public function unlock(User $user, TestAllocation $testAllocation): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }
    public function lock(User $user, TestAllocation $testAllocation): bool
    {
        //
        return $user->hasAnyRole(['head']);
    }
}
