<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'id_user',
        'name', 
        'specialty',
        'phone',
    ];

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'id_doctor', 'id_doctor');
    }

    public function user() 
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}