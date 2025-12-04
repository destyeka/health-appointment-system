<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_role';
    protected $fillable = [
        'role_name',
        'description',
    ];

    // FIX: Route model binding pakai 'id_role' sebagai key untuk parameter {role}
    public function getRouteKeyName()
    {
        return 'id_role';
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'id_role', 'id_permission');
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}