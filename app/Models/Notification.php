<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    protected $fillable = [
        'notification_id',
        'appointment_id',
        'status',
        'message',
        'sent_at',
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}