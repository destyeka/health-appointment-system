<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Prescription;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('prescriptions')->insert([
            [
                'id_record' => 1,
                'medication_name' => 'Paracetamol',
                'dosage' => '500mg',
                'frequency' => '3 times a day',
                'duration' => '5 days',
                'prescribed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_record' => 1,
                'medication_name' => 'Amoxicillin',
                'dosage' => '250mg',
                'frequency' => '2 times a day',
                'duration' => '7 days',
                'prescribed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
