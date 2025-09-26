<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'permission_name' => 'view_appointment',
                'description' => 'melihat daftar appointment',
            ],
            [
                'permission_name' => 'update_appointment_status',
                'description' => 'update status appointment',
            ],
            [
                'permission_name' => 'view_medical_record',
                'description' => 'melihat medical report',
            ],
            [
                'permission_name' => 'view_medical_record',
                'description' => 'melihat medical record',
            ],
            [
                'permission_name' => 'make_medical_record',
                'description' => 'membuat medical report',
            ],
            [
                'permission_name' => 'view_perscription',
                'description' => 'melihat perscription/resep obat',
            ],
            [
                'permission_name' => 'make_perscription',
                'description' => 'membuat perscription/resep obat',
            ],
            [
                'permission_name' => 'view_telemedicine',
                'description' => 'melihat telemedicine',
            ],
            [
                'permission_name' => 'make_appointment',
                'description' => 'membuat appointment',
            ],
            [
                'permission_name' => 'make_telemedicine',
                'description' => 'membuat telemedicine',
            ],
            [
                'permission_name' => 'make_payment',
                'description' => 'membuat payment',
            ],
            [
                'permission_name' => 'view_schedule',
                'description' => 'melihat jadwal dokter',
            ],
            [
                'permission_name' => 'make_schedule',
                'description' => 'membuat jadwal dokter',
            ],
            
        ];
    }
}
