<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'Admin',
                'description' => 'Mengelola sistem, user, dan konfigurasi aplikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Doctor',
                'description' => 'Melakukan konsultasi dan pelayanan medis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Patient',
                'description' => 'Mengakses layanan kesehatan dan melakukan appointment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
