<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $table = 'ticket_history';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    protected $fillable = ['ticket_id', 'performed_by', 'action', 'details', 'created_at'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
