<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'requester_id',
        'assigned_staff_id',
        'category_id',
        'urgency_level',
        'status',
        'ticket_number',
        'other_category',
        'issue_description',
        'resolution_notes'
    ];

    public function requester() {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedStaff() {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function notes() {
        return $this->hasMany(TicketNote::class, 'ticket_id');
    }

}
