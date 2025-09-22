<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\hasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_patient',
        'id_doctor',
        'appointment_date',
        'appointment_time',
        'status',
        'consultation_type'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'time'
    ];
}
