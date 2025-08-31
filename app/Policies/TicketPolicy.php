<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Helper functions for roles.
     */

    protected function isMIS(User $user): bool
    {
        return $user->role === 'mis';
    }

    protected function isRequester(User $user): bool
    {
        return $user->role === 'requester';
    }

    /**
     * View any tickets.
     * Admin and MIS can see all tickets.
     */
    public function viewAny(User $user): bool
    {
        return $this->isMIS($user);
    }

    /**
     * View a specific ticket.
     * Admin and MIS can view all, requesters only their own.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $this->isMIS($user) ||
               $user->id === $ticket->user_id;
    }

    /**
     * Create tickets.
     * Requesters can create, but Admin/MIS should not (they only manage).
     */
    public function create(User $user): bool
    {
        return $this->isRequester($user);
    }

    /**
     * Update a ticket.
     * Admin and MIS can update any, requesters can only update their own
     * (e.g., before it’s being processed).
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $this->isMIS($user) ||
               $user->id === $ticket->user_id;
    }

    /**
     * Delete tickets.
     * Only Admin, or the ticket owner if still pending.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($this->isMIS($user)) {
            return true;
        }

        return $user->id === $ticket->user_id && $ticket->status === 'pending';
    }

    /**
     * Restore tickets (soft deletes).
     * Only Admin.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $this->isMIS($user);
    }

    /**
     * Force delete tickets.
     * Only Admin.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $this->isMIS($user);
    }
}
