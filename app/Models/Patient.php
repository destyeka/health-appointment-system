<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'password',
        'address',
        'insurance_info',
    ];

    protected $casts = [
        'date_of_birth' => 'date'
    ];
}
