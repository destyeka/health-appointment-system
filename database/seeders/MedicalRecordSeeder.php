<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medical_records')->insert([
            [
                'id_appointment' => 001, 
                'diagnosis' => 'Demam dan flu',
                'treatment' => 'Istirahat, minum obat flu',
                'notes' => 'Kontrol ulang 3 hari lagi',
            ],
            [
                'id_appointment' => 002,
                'diagnosis' => 'Sakit gigi',
                'treatment' => 'Cabut gigi + antibiotik',
                'notes' => 'Pasien alergi paracetamol',
            ],
            [
                'id_appointment' => 003,
                'diagnosis' => 'Hipertensi ringan',
                'treatment' => 'Diet rendah garam + obat hipertensi',
                'notes' => 'Cek tekanan darah tiap minggu',
            ],
        ]);
    }
}
