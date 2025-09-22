<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreTicketCommunication extends Model
{
    protected $table = 'pre_ticket_communications';
    protected $fillable = ['requester_id', 'mis_staff_id', 'issue_description', 'suggested_solution', 'created_at'];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function misStaff()
    {
        return $this->belongsTo(User::class, 'mis_staff_id');
    }
}
