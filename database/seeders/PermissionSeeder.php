<?php

namespace Database\Seeders;

use App\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'appointment' => 'appointment',
            'medical_record' => 'medical record',
            'perscription' => 'perscription/resep obat',
            'telemedicine' => 'telemedicine',
            'payment' => 'payment',
            'schedule' => 'jadwal dokter',
            'user' => 'user',
            'doctor' => 'dokter',
            'patient' => 'pasien',
            'notification' => 'notifikasi',
        ];

        $actions = [
            'view' => 'melihat',
            'make' => 'membuat',
            'edit' => 'mengedit',
            'delete' => 'menghapus',
        ];

        $permissions = [];

        foreach ($modules as $module_key => $module_name_id) {
            foreach ($actions as $action_key => $action_name_id) {
                // Payment tidak ada action 'edit', jadi kita skip
                if ($module_key === 'payment' && $action_key === 'edit') {
                    continue;
                }
                
                $permissions[] = [
                    'permission_name' => "{$action_key}_{$module_key}",
                    'description' => "{$action_name_id} {$module_name_id}",
                ];
            }
        }

        Permission::insert($permissions);
    }
}