<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_appointment';

    protected $fillable = [
    'id_patient',
    'id_doctor', // bukan id_doctor_schedule
    'appointment_date',
    'appointment_time',
    'status',
    'consultation_type',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }

    public function doctor()
    {
    return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }

}
