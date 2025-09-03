<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'title',
        'description',
        'category',
        'type',
        'urgency',
        'priority',
        'expected_completion_date',
        'status',
        'priority'
    ];
    public function requester() {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function attachments(){
        return $this->hasMany(TicketAttachment::class);
    }
}
