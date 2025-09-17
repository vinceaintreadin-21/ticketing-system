<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogService
{
    public static function log($action, $ticketId = null, $description = '', $additionalData = [])
    {
        $logData = [
            'user_id' => Auth::id(),
            'ticket_id' => $ticketId,
            'action' => $action,
            'description' => $description,
            'timestamp' => now(),
        ];

        // Merge additional data
        $logData = array_merge($logData, $additionalData);

        Log::info("Ticket Activity: {$action} - {$description}", $logData);

        // Future: Store in database for better activity tracking
        // Activity::create($logData);
    }

    public static function logTicketCreated($ticketId, $ticketTitle)
    {
        self::log('created', $ticketId, "Ticket created: {$ticketTitle}");
    }

    public static function logStatusChanged($ticketId, $oldStatus, $newStatus)
    {
        self::log('status_changed', $ticketId, "Status changed from {$oldStatus} to {$newStatus}");
    }

    public static function logPriorityChanged($ticketId, $newPriority)
    {
        self::log('priority_changed', $ticketId, "Priority changed to {$newPriority}");
    }

    public static function logBulkUpdate($action, $value, $ticketCount)
    {
        self::log('bulk_update', null, "Bulk updated {$ticketCount} tickets: {$action} = {$value}");
    }

    public static function logTicketDeleted($ticketId, $ticketTitle)
    {
        self::log('deleted', $ticketId, "Ticket deleted: {$ticketTitle}");
    }
}
