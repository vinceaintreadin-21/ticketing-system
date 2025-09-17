<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ActivityLogService;

class AdminTicketController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request) {
        $this->authorize('viewAny', Ticket::class);

        $user = Auth::user();
        
        if ($user->role !== 'mis') {
            abort(403);
        }

        $query = Ticket::with(['requester', 'attachments', 'department']);

        // Department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('requester', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->latest()->paginate(15);
        $departments = Department::all();

        return view('admin.tickets.index', compact('tickets', 'departments'));
    }

    public function update(Request $request, Ticket $ticket) {
        $this->authorize('update', $ticket);

        $user = Auth::user();
        
        if ($user->role !== 'mis') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,ongoing,closed,resolved,cancelled',
            'urgency' => 'nullable|in:low,medium,high',
            'priority' => 'nullable|integer|min:0|max:10',
            'expected_completion_date' => 'nullable|date|after_or_equal:today',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Check if user has full control or view-only access
        if (!$this->hasFullControl($user)) {
            return back()->withErrors(['permission' => 'You have view-only access.']);
        }

        $oldStatus = $ticket->status;
        $ticket->update($validated);

        // Log status changes
        if ($oldStatus !== $validated['status']) {
            ActivityLogService::logStatusChanged($ticket->id, $oldStatus, $validated['status']);
        }

        // Log priority changes
        if (isset($validated['priority']) && $ticket->priority !== $validated['priority']) {
            ActivityLogService::logPriorityChanged($ticket->id, $validated['priority']);
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function bulkUpdate(Request $request) {
        $this->authorize('update', Ticket::class);

        $user = Auth::user();
        
        if ($user->role !== 'mis' || !$this->hasFullControl($user)) {
            abort(403);
        }

        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'action' => 'required|in:status,priority,department',
            'value' => 'required'
        ]);

        $tickets = Ticket::whereIn('id', $request->ticket_ids)->get();

        foreach ($tickets as $ticket) {
            switch ($request->action) {
                case 'status':
                    $ticket->update(['status' => $request->value]);
                    break;
                case 'priority':
                    $ticket->update(['priority' => $request->value]);
                    break;
                case 'department':
                    $ticket->update(['department_id' => $request->value]);
                    break;
            }
        }

        ActivityLogService::logBulkUpdate($request->action, $request->value, $tickets->count());

        return back()->with('success', 'Bulk update completed successfully.');
    }

    public function export(Request $request) {
        $this->authorize('viewAny', Ticket::class);

        $user = Auth::user();
        
        if ($user->role !== 'mis') {
            abort(403);
        }

        $query = Ticket::with(['requester', 'department']);

        // Apply filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->get();

        // Generate CSV
        $filename = 'tickets_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Title', 'Description', 'Status', 'Priority', 'Department', 'Requester', 'Created At', 'Updated At']);
            
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->title,
                    $ticket->description,
                    $ticket->status,
                    $ticket->priority,
                    $ticket->department?->name ?? 'N/A',
                    $ticket->requester?->name ?? 'N/A',
                    $ticket->created_at,
                    $ticket->updated_at,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function hasFullControl($user) {
        // Add logic to determine if user has full control or view-only access
        // This could be based on a permission system or user attributes
        return true; // For now, all MIS users have full control
    }
}
