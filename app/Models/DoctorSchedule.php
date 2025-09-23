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
        'time',
        'patient_slot'
    ];

    protected $casts = [
        'time' => 'time',
        'patient_slot' => 'integer'
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }

    public function appointments() {
        return $this->hasMany(Appointment::class, 'id_doctor_schedule');
    }
}
