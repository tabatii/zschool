<?php

namespace App\Policies;

use App\Models\Session;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;

class SessionPolicy
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
    public function view(User $user, Session $session): bool
    {
        return match (panel()->getId()) {
            default => false,
            'admin' => true,
            'teacher' => $session->teacher_id === $user->id,
            'student' => $session->loadMissing('group.students:id')->group->students->contains('id', $user->id),
            'guardian' => $session->loadMissing('group.students:id')->group->students->contains('id', tenant()->getKey()),
        };
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array(panel()->getId(), ['admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Session $session): bool
    {
        return match (panel()->getId()) {
            default => false,
            'admin' => true,
            'teacher' => $session->teacher_id === $user->id,
        };
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Session $session): bool
    {
        return in_array(panel()->getId(), ['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Session $session): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Session $session): bool
    {
        return false;
    }
}
