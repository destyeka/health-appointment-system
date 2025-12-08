<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role; // Tambahkan import untuk Model Role

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

    public function patient()
    {
        return $this->hasOne(Patient::class, 'id_user', 'id_user');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id_user', 'id_user');
    }

    // Catatan: Admin tidak memiliki Model terkait di User.php yang Anda berikan,
    // jadi relasi ini mungkin perlu disesuaikan atau dihapus jika tidak digunakan:
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user', 'id_user');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    /**
     * FIX UTAMA: Mendapatkan nama role dari user
     */
    public function getRoleName(): ?string
    {
        // Memuat relasi role (jika belum dimuat) dan mengembalikan role_name
        return $this->role->role_name ?? null; 
    }
    
    /**
     * Cek apakah user punya permission tertentu
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role 
            && $this->role->permissions()->where('permission_name', $permission)->exists();
    }

    // Helper methods for role checking (Memanggil getRoleName() yang sudah diperbaiki)
    public function isAdmin(): bool
    {
        // PERBAIKAN: Menggunakan huruf kapital 'Admin' sesuai RoleSeeder
        return $this->getRoleName() === 'Admin';
    }
    public function isDoctor(): bool
    {
        // PERBAIKAN: Menggunakan huruf kapital 'Doctor'
        return $this->getRoleName() === 'Doctor';
    }
    
    public function isPatient(): bool
    {
        // PERBAIKAN: Menggunakan huruf kapital 'Patient'
        return $this->getRoleName() === 'Patient';
    }
}