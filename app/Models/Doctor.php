<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DoctorSchedule; // <-- TAMBAHKAN IMPORT INI

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $primaryKey = 'id_doctor';
    protected $fillable = [
        'id_user',
        'name',
        'specialty',
        'phone'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI BARU
    |--------------------------------------------------------------------------
    */
    
    /**
     * Mendapatkan semua jadwal (schedules) yang dimiliki oleh dokter ini.
     */
    public function schedules()
    {
        // Satu Dokter (Doctor) memiliki banyak Jadwal (DoctorSchedule)
        // Foreign key di 'doctor_schedules' adalah 'id_doctor'
        // Local key di 'doctors' adalah 'id_doctor'
        return $this->hasMany(DoctorSchedule::class, 'id_doctor', 'id_doctor');
    }
}
