<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Tambahkan properti ini:
    protected $table = 'notifications'; 
    protected $primaryKey = 'id_notification'; // WAJIB: Sesuai dengan migration Anda
    public $incrementing = true; // Opsional, defaultnya true
    protected $keyType = 'int'; // Opsional, defaultnya int

    protected $fillable = [
        'id_user',
        'status',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public $timestamps = false; // Ini sudah benar karena Anda tidak memiliki kolom created_at/updated_at di migration

    public function appointment() {
        return $this->belongsTo(Appointment::class, 'id_appointment', 'id_appointment');
    }
    
    public function user() // Relasi ke User (penerima notifikasi)
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}