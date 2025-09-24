<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\TicketNote;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TicketHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['requester', 'assignedStaff', 'category'])->get();
        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        // Gate::authorize('create', Ticket::class);

        try {
            Log::info('Ticket creation request received', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'urgency_level' => 'required|in:low,medium,high',
                'issue_description' => 'required|string',
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            // Convert lowercase urgency to proper case for database
            $urgencyMap = [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High'
            ];

            $ticketData = [
                'requester_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'urgency_level' => $urgencyMap[$validated['urgency_level']], // Fixed: use mapped value
                'ticket_number' => 'TCKT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                'status' => 'Pending',
                'issue_description' => $validated['issue_description'],
            ];

            Log::info('Creating ticket with data', $ticketData);

            $ticket = Ticket::create($ticketData);

            Log::info('Ticket created successfully', ['ticket_id' => $ticket->id]);

            // Create ticket history
            try {
                TicketHistory::create([
                    'ticket_id' => $ticket->id,
                    'performed_by' => Auth::id(),
                    'action' => 'Ticket created',
                    'details' => 'Ticket created by requester',
                ]);
                Log::info('Ticket history created successfully');
            } catch (\Exception $e) {
                Log::error('Failed to create ticket history', ['error' => $e->getMessage()]);
                // Don't fail the entire request if history creation fails
            }

            return response()->json($ticket, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Ticket creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        // Gate::authorize('view', Ticket::class);

        $ticket = Ticket::with(['requester', 'assignedStaff', 'category', 'notes.author'])->findOrFail($id);
        return response()->json($ticket);
    }

    public function update(Request $request, $id)
    {
        // Gate::authorize('update', Ticket::class);

        $ticket = Ticket::findOrFail($id);

        $ticket->update($request->only ([
            'assigned_staff_id',
            'status',
            'resolution_notes'
        ]));

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'performed_by' => Auth::id(),
            'action' => 'Ticket updated',
            'details' => 'Ticket updated by staff',
        ]);

        return response()->json($ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'performed_by' => Auth::id(),
            'action' => 'Ticket deleted',
            'details' => 'Ticket deleted by staff',
        ]);

        return response()->json([
            'message' => 'Ticket deleted successfully'
        ]);
    }
}
