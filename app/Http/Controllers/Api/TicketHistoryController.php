<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketHistoryController extends Controller
{
    public function index($ticketId)
    {
        $ticket = Ticket::with('history.performer')->findOrFail($ticketId);
        return response()->json($ticket->history);
    }
}
