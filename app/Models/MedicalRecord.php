<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';
    protected $primaryKey = 'id_record';

    protected $fillable = [
        'id_appointment',
        'id_prescription',
        'diagnosis',
        'treatment',
        'notes',
    ];

    // // Relasi ke Appointment
    // public function appointment()
    // {
    //     // id_appointment foreign key di medical_records
    //     return $this->belongsTo(Appointment::class, 'id_appointment');
    // }

    // // Relasi ke Prescription
    // public function prescription()
    // {
    //     // id_prescription foreign key di medical_records
    //     return $this->belongsTo(Prescription::class, 'id_prescription');
    // }
}
