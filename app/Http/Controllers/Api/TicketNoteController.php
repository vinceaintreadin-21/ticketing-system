<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use App\Models\TicketNote;
use Illuminate\Http\Request;
use App\Models\TicketHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketNoteController extends Controller
{
    public function store(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $validated = $request->validate([
            'note_type' => 'required|in:internal,external',
            'content' => 'required|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;

        $noteTypeMap = [
            'internal' => 'Internal',
            'external' => 'External'
        ];

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('ticket_files', $fileName, 'public');
                $fileType = $file->getClientMimeType();
            }
        } catch (\Exception $e) {
            Log::error('File upload failed', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'File upload failed: '.$e->getMessage()], 500);
        }

        $note = TicketNote::create([
            'ticket_id' => $ticket->id,
            'author_id' => Auth::id(),
            'note_type' => $noteTypeMap[$validated['note_type']],
            'content' => $validated['content'],
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'performed_by' => Auth::id(),
            'action' => 'Note added',
            'details' => 'A new note was added to the ticket',
        ]);

        return response()->json($note, 201);
    }
}

