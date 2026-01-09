<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
