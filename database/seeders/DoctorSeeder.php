<?php

namespace Database\Seeders;

use App\Models\Doctor;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'id_user' => 2,
                'name' => 'dr. Andi Pratama, Sp. JP',
                'specialty' => 'Spesialis Jantung',
                'phone' => '0812-3456-7890'
            ],
            [
                'id_user' => 3,
                'name' => 'dr. Siti Rahma, Sp. A',
                'specialty' => 'Spesialis Anak',
                'phone' => '0857-3344-5566'
            ],
            [
                'id_user' => 4,
                'name' => 'dr. Budi Santoso, Sp. KK',
                'specialty' => 'Spesialis Kulit & Kelamin',
                'phone' => '0821-9876-5432'
            ],
        ];

        foreach($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
