<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class Doctor extends Model
{
    protected $fillable = [
        'name', 
        'specialty',
        'phone',
        'email'
    ];

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'id_doctor', 'id_doctor');
    }
}