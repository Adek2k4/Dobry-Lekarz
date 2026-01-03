<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'ticket_type_id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($model->doctor_id === $model->patient_id) {
                throw new InvalidArgumentException('Nie można zgłosić samego siebie.');
            }
        });

        static::updating(function ($model) {
            if ($model->doctor_id === $model->patient_id) {
                throw new InvalidArgumentException('Nie można zgłosić samego siebie.');
            }
        });
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
