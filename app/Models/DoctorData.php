<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorData extends Model
{
    use HasFactory;

    protected $table = 'doctor_data';

    protected $fillable = [
        'user_id',
        'address_id',
        'specialization_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
}
