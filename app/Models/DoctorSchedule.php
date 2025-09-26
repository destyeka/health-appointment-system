<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_doctor',
        'day',
        'start_time',
        'end_time',
        'patient_slot'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'patient_slot' => 'integer'
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }

    public function appointments() {
        return $this->hasMany(Appointment::class, 'id_doctor_schedule');
    }
}
