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
        'grand_total',
        'booking_is_paid',
        'repayment_is_paid',
    ];

    protected $casts = [
        'grand_total' => 'decimal:2',
        'booking_is_paid' => 'boolean',
        'repayment_is_paid' => 'boolean'
    ];


    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'id_appointment', 'id_appointment');
    }

    public function paymentDetails() {
        return $this->hasMany(
            PaymentDetail::class
        );
    }
}