<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $primaryKey = 'id_permission';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'permission_name',
        'description',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'id_permission', 'id_role');
    }
}
