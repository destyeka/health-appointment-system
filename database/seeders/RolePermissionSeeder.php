<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        $admin_role = Role::where('role_name', 'Admin')->first();
        $admin_permissions = Permission::pluck('id_permission');
        $admin_role->permissions()->attach($admin_permissions);

        // doctor
        $doctor_role = Role::where('role_name', 'Doctor')->first();
        $doctor_permissions = Permission::whereIn('permission_name', [
            'view_appointment',
            'update_appointment',
            'view_medical_record',
            'make_medical_record',
            'view_perscription',
            'make_perscription',
            'view_telemedicine',
            'view_schedule'
        ])->pluck('id_permission');
        $doctor_role->permissions()->attach($doctor_permissions);

        // patient
        $patient_role = Role::where('role_name', 'Patient')->first();
        $patient_permissions = Permission::whereIn('permission_name', [
            'view_appointment',
            'update_appointment',
            'view_medical_record',
            'view_perscription',
            'view_telemedicine',
            'view_schedule'
        ])->pluck('id_permission');
        $patient_role->permissions()->attach($patient_permissions);
    }
}
