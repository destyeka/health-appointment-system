<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Add new queue management permission
        $queuePermission = Permission::create([
            'permission_name' => 'manage_queue',
            'description' => 'Mengelola antrean pasien'
        ]);

        // Assign permission to Admin and Doctor roles
        $adminRole = Role::where('role_name', 'Admin')->first();
        $doctorRole = Role::where('role_name', 'Doctor')->first();

        if ($adminRole) {
            $adminRole->permissions()->attach($queuePermission->id_permission);
        }
        if ($doctorRole) {
            $doctorRole->permissions()->attach($queuePermission->id_permission);
        }
    }

    public function down(): void
    {
        $queuePermission = Permission::where('permission_name', 'manage_queue')->first();
        
        if ($queuePermission) {
            // Remove permission from roles
            $queuePermission->roles()->detach();
            // Delete the permission
            $queuePermission->delete();
        }
    }
};