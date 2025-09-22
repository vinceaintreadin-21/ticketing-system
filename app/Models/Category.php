<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }
}
