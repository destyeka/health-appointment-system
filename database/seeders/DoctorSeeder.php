<?php

namespace Database\Seeders;

use App\Models\Doctor;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'dr. Andi Pratama, Sp. JP',
                'specialty' => 'Spesialis Jantung',
                'phone' => '0812-3456-7890',
                'email' => 'dr.andipratama@mandalamedika.com'
            ],
            [
                'name' => 'dr. Siti Rahma, Sp. A',
                'specialty' => 'Spesialis Anak',
                'phone' => '0857-3344-5566',
                'email' => 'dr.sitirahma@mandalamedika.com'
            ],
            [
                'name' => 'dr. Budi Santoso, Sp. KK',
                'specialty' => 'Spesialis Kulit & Kelamin',
                'phone' => '0821-9876-5432',
                'email' => 'dr.budisantoso@mandalamedika.com'
            ],
        ];

        foreach($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
