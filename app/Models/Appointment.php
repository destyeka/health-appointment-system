<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Doctor;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';
    public $incrementing = false;

    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id', 'date_of_appointment', 'time_of_appointment', 'status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }
}