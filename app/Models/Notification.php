<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_notification';
    protected $fillable = [
        'id_notification',
        'id_appointment',
        'status',
        'message',
        'sent_at',
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'id_appointment');
    }
}