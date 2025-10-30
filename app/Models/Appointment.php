<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_appointment';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            // Assign queue number based on appointment time
            $queueNumber = static::where('id_doctor', $appointment->id_doctor)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->whereTime('appointment_time', '<=', $appointment->appointment_time)
                ->count() + 1;
            
            $appointment->queue_number = $queueNumber;
        });
    }

    protected $fillable = [
        'id_patient',
        'id_doctor',
        'appointment_date',
        'appointment_time',
        'status',
        'consultation_type',
        'queue_number',
        'is_called',
        'called_at',
        'estimated_minutes_remaining'
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
