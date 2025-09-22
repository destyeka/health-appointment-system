<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'id_payment';
    
    protected $fillable = [
        'id_appointment',
        'amount',
        'method',
        'status_payment',
    ];


    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'idappointment');
    }
}