<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class Patient extends Model
{
    protected $primaryKey = 'patient_id';
    public $incrementing = false;

    protected $fillable = [
        'patient_id', 'patient_name', 'phone_number', 'patient_email', 'date_of_birth'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'patient_id');
    }
}
