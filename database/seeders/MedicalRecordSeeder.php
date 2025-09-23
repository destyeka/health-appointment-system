<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord; // panggil modelnya

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        MedicalRecord::create([
            'id_appointment' => 1,
            'diagnosis' => 'Demam dan flu',
            'treatment' => 'Istirahat, minum obat flu',
            'notes' => 'Kontrol ulang 3 hari lagi',
        ]);

        MedicalRecord::create([
            'id_appointment' => 2,
            'diagnosis' => 'Sakit gigi',
            'treatment' => 'Cabut gigi + antibiotik',
            'notes' => 'Pasien alergi paracetamol',
        ]);

        MedicalRecord::create([
            'id_appointment' => 3,
            'diagnosis' => 'Hipertensi ringan',
            'treatment' => 'Diet rendah garam + obat hipertensi',
            'notes' => 'Cek tekanan darah tiap minggu',
        ]);
    }
}
