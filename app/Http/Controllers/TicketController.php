<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ActivityLogService;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index() {
        $this->authorize('viewAny', Ticket::class);

        $user = Auth::user();

        if ($user->role === 'mis') {
            // Redirect MIS users to admin ticket management
            return redirect()->route('admin.tickets.index');
        } else {
            // Regular users see only their tickets
            $tickets = Ticket::with('attachments')
                ->where('requester_id', $user->id)
                ->latest()
                ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create() {
        $this->authorize('create', Ticket::class);
        return view('tickets.create');
    }

    public function store(Request $request) {
        $this->authorize('create', Ticket::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:bug,new_request,fix,enhancement,inquiry',
            'category' => 'nullable|in:developer,technicians',
            'attachments.*' => 'nullable|mimes:jpeg,png,jpg,mp4,mov,ogg,qt'
        ]);

        $ticket = Ticket::create([
            'requester_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category' => $validated['category'] ?? null,
            'department_id' => Auth::user()->department_id,
            'status' => 'pending',
            'priority' => null,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket_attachments', 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                ]);
            }
        }

        // Log activity
        ActivityLogService::logTicketCreated($ticket->id, $ticket->title);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function update(Request $request, Ticket $ticket) {
        $this->authorize('update', $ticket);

        $user = Auth::user();

        if ($user->role === 'requester') {
            $validated = $request->validate([
                'description' => 'required|string',
                'attachments.*' => 'nullable|mimes:jpeg,png,jpg,mp4,mov,ogg,qt'
            ]);

            if ($ticket->status !== 'pending') {
                return back()->withErrors(['status' => 'You can only update tickets that are still pending.']);
            }

            $ticket->update([
                'description' => $validated['description'],
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('ticket_attachments', 'public');
                    TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'file_path' => $path,
                    ]);
                }
            }

        } elseif ($user->role === 'mis') {
            // Redirect MIS users to admin update
            return redirect()->route('admin.tickets.update', $ticket);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function delete(Ticket $ticket) {
        $this->authorize('delete', $ticket);

        $ticketTitle = $ticket->title;
        $ticket->delete();

        // Log activity
        ActivityLogService::logTicketDeleted($ticket->id, $ticketTitle);

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
