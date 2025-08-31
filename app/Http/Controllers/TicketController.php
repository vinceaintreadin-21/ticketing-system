<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index() {

        $this->authorize('viewAny', Ticket::class);

        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'mis') {
            $tickets = Ticket::with(['requester', 'attachments'])->latest()->get();
        } else {
            $tickets = Ticket::with('attachments')->where('user_id', $user->id)->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create() {

        $this->authorize('create', Ticket::class);
        return view('tickets.create');
    }

    public function store(Request $request) {
        $this->authorize('create', Ticket::class);

        $ticket = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:bug,new_request,fix,enhancement,inquiry',
            'category' => 'nullable|in:developer,technicians',
            'attachments.*' => 'nullable|mimes:jpeg,png,jpg,mp4,mov,ogg,qt'
        ]);

        $ticket = Ticket::create([
            'requester_id' => Auth::id(),
            'title' => $ticket['title'],
            'description' => $ticket['description'],
            'type' => $ticket['type'],
            'category' => $ticket['category'] ?? null,
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
            $validated = $request->validate([
                'status' => 'required|in:pending,ongoing,closed,resolved,cancelled',
                'urgency' => 'nullable|in:low,medium,high',
                'priority' => 'nullable|integer|min:0',
                'expected_completion_date' => 'nullable|date|after_or_equal:today',
            ]);

            $ticket->update($validated);

        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function delete(Ticket $ticket) {

        $this->authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
