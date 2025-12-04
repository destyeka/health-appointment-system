<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_payment_detail';

    protected $fillable = [
        'id_payment',
        'amount',
        'method',
        'payment_type',
        'status_payment',
        'va_number',
        'payment_url',
        'order_number',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function payment(){
        return $this->belongsTo(Payment::class, 'id_payment','id_payment');
    }

}
