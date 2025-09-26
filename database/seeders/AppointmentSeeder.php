<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $patientIds = Patient::pluck('patient_id');
        $doctorIds = Doctor::pluck('doctor_id');

        for ($i = 0; $i < 15; $i++) {
            $appointmentTime = $faker->dateTimeBetween('now', '+1 month');

            Appointment::create([
                'patient_id' => $faker->randomElement($patientIds),
                'doctor_id' => $faker->randomElement($doctorIds),
                'date_of_appointment' => $appointmentTime->format('Y-m-d'),
                'time_of_appointment' => $appointmentTime->format('H:i:s'),
                'status' => $faker->randomElement(['scheduled', 'completed', 'canceled']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}