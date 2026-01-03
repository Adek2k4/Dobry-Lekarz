<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'postal_code',
        'street',
        'house_number',
    ];
}
