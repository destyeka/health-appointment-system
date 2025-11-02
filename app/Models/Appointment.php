<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function doctorSchedule() {
        return $this->belongsTo(DoctorSchedule::class, 'id_doctor_schedule', 'id_doctor_schedule');
    }

    public function patient() {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }

    public function payment() {
        return $this->hasOne(Payment::class, 'id_appointment', 'id_appointment');
    }

    public function medicalRecord() {
        return $this->hasOne(MedicalRecord::class, 'id_appointment', 'id_appointment');
    }

    public function teleMedicine() {
        return $this->hasOne(TeleMedicine::class, 'id_appointment', 'id_appointment');
    }

    public function notifications() {
        return $this->hasMany(Notification::class, 'id_appointment', 'id_appointment');
    }
}