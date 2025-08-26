<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'priority'
    ];
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies() {
        return $this->hasMany(Replies::class, 'ticket_id');
    }
}
