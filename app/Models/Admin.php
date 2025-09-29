<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'name', 
        'specialty',
        'phone',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
