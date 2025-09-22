<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemedicine extends Model
{
    use HasFactory;

    protected $table = 'telemedicine';

    protected $fillable = [
        'id_session',
        'id_appointment',
        'session_link',
        'start_time',
        'end_time',
        'status',
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'id_appointment');
    }
}