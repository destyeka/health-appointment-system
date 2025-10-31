<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_payment',
        'amount',
        'method',
        'payment_type',
        'status_payment'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payment(){
        return $this->belongsTo(Payment::class, 'id_payment','id_payment');
    }

}
