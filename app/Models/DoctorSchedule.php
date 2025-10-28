<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     */
    protected $table = 'doctor_schedules';

    /**
     * Primary key untuk tabel.
     */
    protected $primaryKey = 'id_doctor_schedule';

    /**
     * Kolom yang diizinkan untuk diisi.
     */
    protected $fillable = [
        'id_doctor',
        'day',
        'start_time',
        'end_time',
        'patient_slot'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];


    /**
     * Menentukan apakah model harus mencatat timestamps (created_at, updated_at).
     * Tabel Anda tidak memilikinya, jadi kita set ke false.
     */
    public $timestamps = false;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }
}
