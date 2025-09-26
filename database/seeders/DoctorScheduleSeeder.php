<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            [
                'id_doctor' => '1',
                'day' => 'Senin',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'patient_slot' => 10
            ],
            [
                'id_doctor' => '2',
                'day' => 'Senin',
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
                'patient_slot' => 8
            ],
            [
                'id_doctor' => '3',
                'day' => 'Senin',
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'patient_slot' => 5
            ],
        ];
    }
}
