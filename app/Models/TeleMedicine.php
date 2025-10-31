<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemedicine extends Model
{
    use HasFactory;

    protected $table = 'telemedicines';

    protected $fillable = [
        'id_appointment',
        'session_link',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'end_time'
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'id_appointment', 'id_appointment');
    }
}