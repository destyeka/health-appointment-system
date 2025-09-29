<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    protected $primaryKey = 'id_user';

    public function patients()
    {
        return $this->hasMany(Patient::class, 'id_user', 'id_user');
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'id_user', 'id_user');
    }
}
