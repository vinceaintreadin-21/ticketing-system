<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketNote extends Model
{
    protected $fillable = [
        'ticket_id', 'author_id', 'note_type', 'content',
        'file_name', 'file_path', 'file_type'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
