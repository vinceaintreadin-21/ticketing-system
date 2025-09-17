<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users() {
        return $this->hasMany(User::class, 'department_id');
    }

    public function tickets() {
        return $this->hasMany(Ticket::class, 'department_id');
    }
}
