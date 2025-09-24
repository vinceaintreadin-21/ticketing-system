<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $ticket->requester_id === $user->id
            || $ticket->assigned_staff_id === $user->id
            || $user->roles->contains('role_name', 'mis');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->roles()->contains('role_name', 'requester'); //a requester can create tickets
    }

    /**
     * Determine whether the user can update the model.
     */

    public function updateUrgency(User $user, Ticket $ticket): bool
    {
        return $user->roles()->contains('role_name', 'mis'); //only mis can update urgency
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->roles->contains('role_name', 'mis')
            || ($user->roles->contains('role_name', 'staff') && $ticket->assigned_staff_id === $user->id); //mis can update any ticket's progress, staff can update the progress only in their assigned tickets
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}
