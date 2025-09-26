<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'address',
        'insurance_info',
    ];

    protected $casts = [
        'date_of_birth' => 'date'
    ];

    public function appointments() {
        return $this->hasMany(Appointment::class, 'id_patient', 'id_patient');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
