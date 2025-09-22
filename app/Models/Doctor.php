<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class Doctor extends Model
{
    protected $primaryKey = 'doctor_id';
    public $incrementing = false;

    protected $fillable = [
        'doctor_id', 'doctor_name', 'specialty'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'doctor_id');
    }
}