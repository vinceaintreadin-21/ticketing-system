<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'file_path',
        'file_type',
        'file_name',
        'file_size',
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
