<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointments')->insert([
            [
                'id_patient' => 5, // pastikan patient dengan id 1 ada
                'id_doctor_schedule' => 1, // pastikan schedule dengan id 1 ada
                'appointment_date' => '2025-10-01',
                'appointment_time' => '09:00:00',
                'status' => 'scheduled',
                'consultation_type' => 'offline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_patient' => 6,
                'id_doctor_schedule' => 1,
                'appointment_date' => '2025-10-02',
                'appointment_time' => '13:00:00',
                'status' => 'on_going',
                'consultation_type' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_patient' => 7,
                'id_doctor_schedule' => 2,
                'appointment_date' => '2025-10-05',
                'appointment_time' => '10:30:00',
                'status' => 'finished',
                'consultation_type' => 'offline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
