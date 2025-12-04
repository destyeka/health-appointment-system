<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('patients')->insert([
            [
                'id_user' => 5,
                'name' => 'Budi Santoso',
                'gender' => 'Laki-laki',
                'date_of_birth' => '1990-05-12',
                'phone' => '081234567890',
                'address' => 'Jl. Merpati No.10 Jakarta',
                'insurance_info' => 'BPJS 12345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 6,
                'name' => 'Siti Aminah',
                'gender' => 'Perempuan',
                'date_of_birth' => '1985-09-23',
                'phone' => '081345678901',
                'address' => 'Jl. Kenanga No.7 Bandung',
                'insurance_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 7,
                'name' => 'Andi Prasetyo',
                'gender' => 'Laki-laki',
                'date_of_birth' => '2000-02-14',
                'phone' => '081987654321',
                'address' => 'Jl. Melati No.15 Surabaya',
                'insurance_info' => 'Prudential 987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
